var req = require('xhr').XMLHttpRequest;

req.defaultSettings.host = 'http://github.com/';

var client = req();
var creq = client.open('GET', 'drench/phecma');
creq.setRequestHeader('Referer', 'http://example.com/');

print(creq.send().responseText);

print(client.getResponseHeader('Server'));
