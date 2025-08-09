import requests
import time

ids = []

with open('assets.txt', 'r') as fh:
	line = fh.readline()
	while line:
		ids.append(int(line.strip()))
		line = fh.readline()

jar = requests.cookies.RequestsCookieJar()

jar.set('.ROBLOSECURITY', '<roblo security cookie LLL>')

for id in ids:
	print("DOWNLOADING " + str(id))
	url = 'http://assetdelivery.roblox.com/v1/asset/?id='+str(id)
	r = requests.get(url, cookies=jar)

	with open(str(id), 'wb') as f:
		f.write(r.content)
	
	time.sleep(1)
