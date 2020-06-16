#!/usr/bin/python

# SUAS: Screenshot - Upload - Annotate - Share
# https://github.com/uuencode/suas

# SETTINGS

upload_key = 'SECURITYSTRINGNOSPACES' # obvious?
upload_url = 'https://URL.COM/FOLDER/suas_remote.php' # obvious?

path_image = '/tmp/shot.png' # any path/file with permissions to write
scrot_args = 'scrot -o ' + path_image # default scrot options - fullscreen
browser_go = 'firefox '+upload_url+'?markerjs &' # browser command after upload

# -----

import sys
import os
import requests

# scrot options - sel = select area
if len(sys.argv)>1 and sys.argv[1] == 'sel':
	scrot_args = 'scrot -so ' + path_image

# scrot options - win = active window
if len(sys.argv)>1 and sys.argv[1] == 'win':
	scrot_args = 'scrot -uo ' + path_image

# launch scrot
os.system(scrot_args)

# upload
with open(path_image, 'rb') as img:
  name_img= os.path.basename(path_image)
  files= {upload_key: (name_img,img,'multipart/form-data',{'Expires': '0'}) }
  with requests.Session() as s:
    r = s.post(upload_url,files=files)

# delete file after upload
os.remove(path_image)

# launch browser
os.system(browser_go)
