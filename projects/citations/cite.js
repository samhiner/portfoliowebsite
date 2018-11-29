//TODO comment all of this lol
//TODO cite access dates
//TODO italics and Times New Roman 12 pt double spaced
//TODO page margins

function leadingZero(input, spaces) {
	if (input.length < spaces) {
		return leadingZero('0' + input)
	} else {
		return input
	}
}

function onStart() {
	currDate = new Date();
	currMonth = leadingZero(String(currDate.getMonth() + 1), 2);
	currDay = leadingZero(String(currDate.getDate()), 2);

	document.getElementById('accessDate').value = String(currDate.getFullYear()) + '-' + currMonth + '-' + currDay;
}

function authChange() {
	let authNum = Number(document.getElementById('authNum').value);

	let auth1 = document.getElementById('auth1').style.display;
	let auth2 = document.getElementById('auth2').style.display;

	if (authNum == 2) {
		document.getElementById('auth1').style.display = 'block';
		document.getElementById('auth2').style.display = 'block';
	} else if ((authNum == 1) || (authNum > 2)) {
		document.getElementById('auth1').style.display = 'block';
		document.getElementById('auth2').style.display = 'none';
	} else {
		document.getElementById('auth1').style.display = 'none';
		document.getElementById('auth2').style.display = 'none';
	}

}

function createName(name, ord) {
	if (name[1] == '') {
		return name[0];
	}

	if (ord == 1) {
		return name[1] + ', ' + name[0];
	} else {
		return name[0] + ' ' + name[1];
	}
}

function createInText(name) {
	if (name[1] == '') {
		return name[0]
	} else {
		return name[1]
	}
}

function cite() {
	let authNum = document.getElementById('authNum').value;

	var name1 = createName([document.getElementById('firstName1').value, document.getElementById('lastName1').value], 1)
	var name2 = createName([document.getElementById('firstName2').value, document.getElementById('lastName2').value], 2)

	if (authNum > 2) {
		var auth = name1 + ' et al. ';
	} else if (authNum == 2) {
		var auth = name1 + ', and ' + name2 + '. '
	} else if (authNum == 1) {
		var auth = name1 + '. ';
	} else {
		var auth = ''
	}

	var article = '"' + document.getElementById('article').value + '". ';

	var website = '<span class="website">' + document.getElementById('website').value + '</span>, '

	var publisher = document.getElementById('publisher').value + ', '

	if (document.getElementById('publisher').value == document.getElementById('website').value) {
		publisher = ''
	}

	if (document.getElementById('pubYear').value == -1) {
		var pubDate = '';
	} else {
		var pubDate = document.getElementById('pubYear').value + ', ';
	}

	var url = document.getElementById('url').value + '. ';

	var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];

	var rawAccessDate = document.getElementById('accessDate').value.split('-')

	while (String(rawAccessDate[2])[0] == '0') {
		rawAccessDate[2] = rawAccessDate[2].substr(1);
	}

	var accessDate = 'Accessed ' + rawAccessDate[2] + ' ' + months[rawAccessDate[1] - 1] + ' ' + rawAccessDate[0];

	document.getElementById('citation').innerHTML = auth + article + website + publisher + pubDate + url + accessDate + '.';

	inText1 = createInText([document.getElementById('firstName1').value, document.getElementById('lastName1').value]);
	inText2 = createInText([document.getElementById('firstName2').value, document.getElementById('lastName2').value]);

	if (authNum == 1) {
		var inTextCite = inText1;
	} else if (authNum == 2) {
		var inTextCite = inText1 + ', and ' + inText2
	} else if (authNum > 2) {
		var inTextCite = inText1 + ' et al.';
	} else {
		var inTextCite = '"' + document.getElementById('article').value + '"';
	}

	document.getElementById('in-text').innerHTML = '(' + inTextCite + ')';
}

onStart();