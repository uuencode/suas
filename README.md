# suas

Fast and handy Linux tool to **S**hot → **U**pload → **A**nnotate → **S**hare screenshots from your Linux desktop

![suas demo](/this-is-not-GIF.webp "void")

https://github.com/uuencode/suas  
In action: https://youtu.be/U937QthUfiU

## Requirements

### Remote

Any shared hosting service with PHP

### Local

- Any Linux computer
- [Scrot](https://en.wikipedia.org/wiki/Scrot) - available in all repos

## How to install

### Remote

- Open `suas_remote.php` with a text editor (look for *SETTINGS*)
	- Replace the default `$uploadkey` with a random security string
- Your hosting space → create a new directory `suas` → CHMOD it to 777 → upload `suas_remote.php` in it so that the URL to `suas_remote.php` is `https://WEBSITE.COM/suas/suas_remote.php`

### Local

- Install scrot - scrot is available in all repos; Debian/Ubuntu: `sudo apt install scrot`
- Put `suas_local.py` in your home directory and make it executable
- Open `suas_local.php` with a text editor (look for *SETTINGS*)
	- Set the URL of `suas_remote.php` e.g. `https://WEBSITE.COM/suas/suas_remote.php`
	- Replace `$upload_key` with the same security string from `suas_remote.php`
- Set keyboard shortcuts to launch `suas_local.py` (*sel = select area; win = active window*)
	- `PrintScreen` - `/home/USER/suas_local.py`
	- `CTRL` + `PrintScreen` - `/home/USER/suas_local.py win`
	- `SHIFT` + `PrintScreen` - `/home/USER/ suas_local.py sel`

### Security

- The PHP script accepts as uploads only files named `$uploadkey`
- `Copy URL` works on SSL hosts only

### Privacy
The PHP script loads [MarkerJS](https://markerjs.com/) from CDN. In case you do not trust CloudFlare put the latest minified version in the same folder and adjust accordingly `<script src="https://unpkg.com/markerjs"></script>` in `suas_remote.php`

### Obscurity

- Set a different numeric default filename for RAWSHOT in `suas_remote.php` → Settings
- Rename `suas_remote.php`
- Provide an empty `index.html` in the same folder

## Credit

All credit for the image processing go to [MarkerJS](https://markerjs.com/) and [Alan Mendelevich](https://ailon.org/)

## License

MIT