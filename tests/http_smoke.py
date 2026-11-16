"""Uji integrasi pada database khusus pengujian, server PHP sudah berjalan.
Jalankan: python3 tests/http_smoke.py http://127.0.0.1:8087
Data pengujian dibuat lalu dihapus melalui aplikasi.
"""
import http.cookiejar
import re
import sys
import urllib.error
import urllib.parse
import urllib.request

base = sys.argv[1] if len(sys.argv) > 1 else 'http://127.0.0.1:8087'
client = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))
checks = 0

def request(path, data=None):
    body = urllib.parse.urlencode(data).encode() if data is not None else None
    try:
        with client.open(base + '/' + path, body) as r:
            return r.status, r.read().decode(), r.url
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode(), e.url

def check(condition, message):
    global checks
    if not condition:
        raise AssertionError(message)
    checks += 1
    print('PASS', message)

def token(page):
    return re.search(r'name="csrf" value="([a-f0-9]+)"', page).group(1)

def submit(path, values):
    _, page, _ = request(path)
    return request(path, dict(values, csrf=token(page)))

status, page, url = request('index.php')
check(status == 200 and url.endswith('login.php'), 'Anonymous list redirects to login')
for route in ['tambah.php', 'edit.php?id=1', 'hapus.php?id=1', 'detail.php?id=1', 'profile.php', 'dashboard.php']:
    check(request(route)[2].endswith('login.php'), 'Login required: ' + route)
check(request('login.php', {'email':'andi@ukrida.ac.id','password':'123456'})[0] == 403, 'CSRF required for login')
check('Email atau password salah' in submit('login.php', {'email':"' OR 1=1 --",'password':'bad'})[1], 'SQL injection does not bypass login')
status, page, url = submit('login.php', {'email':'andi@ukrida.ac.id','password':'123456'})
check(status == 200 and url.endswith('dashboard.php') and '83.40' in page, 'Login and original weighted grade')
check('Andi Wijaya' in request('index.php')[1], 'List reads seeded students')
for route in ['edit.php?id=abc','hapus.php?id=-1','detail.php?id=1.2']:
    check(request(route)[0] == 400, 'Invalid ID rejected: '+route)
check(request('edit.php?id=9999999')[0] == 404, 'Missing student returns 404')
values = {'nim':'999000111','nama':'Pengujian <script>alert(1)</script>','email':'crud-test@example.invalid','id_prodi':'1','nilai_tugas':'80','nilai_uts':'70','nilai_uas':'90','kehadiran':'85','password':'uji123456'}
check(request('tambah.php', values)[0] == 403, 'Create rejects missing CSRF')
check('harus angka 0–100' in submit('tambah.php', dict(values,nilai_tugas='101'))[1], 'Server validates grade range')
check('Pilih program studi' in submit('tambah.php', dict(values,id_prodi='9999'))[1], 'Server validates foreign key selection')
status, page, url = submit('tambah.php', values)
check(status == 200 and url.endswith('index.php') and 'berhasil ditambahkan' in page, 'Create and redirect')
check('&lt;script&gt;' in page and '<script>alert(1)</script>' not in page, 'Stored HTML is escaped')
row = next(r for r in re.findall(r'<tr>.*?</tr>',page,re.S) if '999000111' in r)
id = re.search(r'edit.php\?id=(\d+)',row).group(1)
check('sudah digunakan' in submit('tambah.php',values)[1], 'Duplicate NIM/email handled')
check('Simpan Perubahan' in request('edit.php?id='+id)[1], 'Edit form reads existing record')
values.update(nama='Mahasiswa Uji Diperbarui',nilai_uas='95',password='')
check('berhasil diperbarui' in submit('edit.php?id='+id,values)[1], 'Update with empty password')
check('83.00' in request('detail.php?id='+id)[1], 'Detail reflects updated grade')
check('Mahasiswa Uji Diperbarui' in request('hapus.php?id='+id)[1], 'Delete confirmation identifies record')
check('Mahasiswa Uji Diperbarui' in request('index.php')[1], 'GET confirmation does not delete')
check(request('hapus.php?id='+id,{})[0] == 403, 'Delete requires CSRF')
check(submit('hapus.php?id=1',{})[0] == 422, 'Current account cannot delete itself')
submit('logout.php',{})
check(submit('login.php',{'email':values['email'],'password':'uji123456'})[2].endswith('dashboard.php'), 'Created password hash works; empty update preserves password')
# Update own NIM and verify the active session follows the changed identity.
values['nim']='999000112'
check('berhasil diperbarui' in submit('edit.php?id='+id,values)[1], 'Update own NIM')
check('Mahasiswa Uji Diperbarui' in request('dashboard.php')[1], 'Session follows updated NIM')
submit('logout.php',{})
submit('login.php',{'email':'andi@ukrida.ac.id','password':'123456'})
check('berhasil dihapus' in submit('hapus.php?id='+id,{})[1], 'Delete and redirect')
check(request('detail.php?id='+id)[0] == 404, 'Deleted record absent')
submit('logout.php',{})
check(request('index.php')[2].endswith('login.php'), 'Logout invalidates session')
print(f'{checks} checks passed.')
