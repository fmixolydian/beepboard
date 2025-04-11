'use strict';

const MODS = {
	"abyssbox":    {'url': 'https://choptop84.github.io/abyssbox-app/'},
	"beepbox":     {'url': 'https://www.beepbox.co'},
	"goldbox":     {'url': 'https://aurysystem.github.io/goldbox/'},
	"jummbox":     {'url': 'https://jummb.us'},
	"modbox":      {'url': 'https://moddedbeepbox.github.io/3.0/'},
	"pandorasbox": {'url': 'https://paandorasbox.github.io/'},
	"sandbox":     {'url': 'https://fillygroove.github.io/sandbox-3.1/'},
	"slarmoosbox": {'url': 'https://slarmoo.github.io/slarmoosbox/website/'},
	"ultrabox":    {'url': 'https://ultraabox.github.io/'},
	"wackybox":    {'url': 'https://bluecatgamer.github.io/Wackybox/'},
	
};

function validateSubmitSongForm() {
	if (document.getElementById('ModTitle').innerText == 'unknown') {
		alert('You must use a valid beepmod.');
		return false;
	}
	
	return true;
}

function submit_onUrlChange(e) {
	let ModLogo  = document.querySelector("#ModLogo");
	let ModTitle = document.querySelector("#ModTitle");
	
	let SongMod = "unknown";
	
	for (const [modname, mod] of Object.entries(MODS)) {
		if (e.target.value.startsWith(mod.url)) {
			SongMod = modname;
			break;
		}
	}
	
	console.log(SongMod);
	ModLogo.src = "/assets/mods/" + SongMod + ".png";
	ModTitle.innerText = SongMod;
}

function init() {
	
}

window.addEventListener('load', init);
