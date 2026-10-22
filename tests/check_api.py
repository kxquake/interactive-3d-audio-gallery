"""Exercise PHP in an isolated temporary copy; never change the working database."""
import argparse
from contextlib import closing
import json
from pathlib import Path
import shutil
import socket
import sqlite3
import subprocess
import tempfile
import time
import urllib.error
import urllib.request

parser = argparse.ArgumentParser()
parser.add_argument('--php', default='php')
args = parser.parse_args()
root = Path(__file__).resolve().parents[1]

with tempfile.TemporaryDirectory(prefix='jb-gallery-api-') as directory:
    fixture = Path(directory)
    shutil.copy2(root / 'index.php', fixture / 'index.php')
    shutil.copy2(root / 'index.html', fixture / 'index.html')
    shutil.copytree(root / 'application', fixture / 'application')
    with socket.socket() as listener:
        listener.bind(('127.0.0.1', 0))
        port = listener.getsockname()[1]
    with tempfile.TemporaryFile() as log:
        server = subprocess.Popen([args.php, '-S', f'127.0.0.1:{port}', '-t', str(fixture)],
                                  stdout=log, stderr=log)
        try:
            base = f'http://127.0.0.1:{port}/index.php'

            def request(query='', method='GET'):
                req = urllib.request.Request(base + query, method=method)
                try:
                    response = urllib.request.urlopen(req, timeout=5)
                except urllib.error.HTTPError as error:
                    response = error
                with response:
                    return response.status, response.read().decode(), response.headers

            for attempt in range(50):
                try:
                    assert request()[0] == 200
                    break
                except (urllib.error.URLError, ConnectionError):
                    if server.poll() is not None:
                        raise RuntimeError('PHP server exited; check the PHP path/modules')
                    time.sleep(0.1)
            else:
                raise RuntimeError('PHP server did not start')

            status, body, headers = request('?action=apiGetProducts')
            assert status == 200 and len(json.loads(body)) == 3
            assert 'application/json' in headers['Content-Type']
            assert request('?action=apiSetup')[0] == 404
            assert request('?action=__construct')[0] == 404
            assert request('?action[]=apiGetProducts')[0] == 404
            assert request('?action=apiGetProducts', 'POST')[0] == 405
            for value in ('', '0', '-1', '1x', '1.5', '01'):
                assert request('?action=apiGetProduct&id=' + value)[0] == 400
            assert request('?action=apiGetProduct')[0] == 400
            assert request('?action=apiGetProduct&id[]=1')[0] == 400
            assert request('?action=apiGetProduct&id=999')[0] == 404
            assert json.loads(request('?action=apiGetProduct&id=3')[1])['title'] == 'Microphone'

            database = fixture / 'db/products.db'
            with closing(sqlite3.connect(database)) as connection, connection:
                connection.execute("UPDATE products SET title='Retained test value' WHERE id=1")
            assert json.loads(request('?action=apiGetProduct&id=1')[1])['title'] == 'Retained test value'
            assert request('?action=apiSetup')[0] == 404
            assert json.loads(request('?action=apiGetProduct&id=1')[1])['title'] == 'Retained test value'

            # Only mutate this test's temporary catalogue to test atomic seeding/error output.
            with closing(sqlite3.connect(database)) as connection, connection:
                connection.execute('DELETE FROM products')
            (fixture / 'application/model/data.json').write_text('{ invalid', encoding='utf-8')
            status, body, _ = request('?action=apiGetProducts')
            assert status == 500 and json.loads(body) == {'error': 'Product data is unavailable'}
            assert str(fixture) not in body
            with closing(sqlite3.connect(database)) as connection, connection:
                assert connection.execute('SELECT COUNT(*) FROM products').fetchone()[0] == 0
            shutil.copy2(root / 'application/model/data.json', fixture / 'application/model/data.json')
            assert len(json.loads(request('?action=apiGetProducts')[1])) == 3
            print('PASS: API routes, validation, fresh DB, no public reseed, retained rows, generic errors and recovery.')
        finally:
            server.terminate()
            server.wait(timeout=10)
