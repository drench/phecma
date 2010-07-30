var app = function (req) {

    var rc = 200;

    if (req.queryString.length) {
        rc = 402;
    }

    return {
        status: rc,
        headers: { Referer: 'http://example.net/' },
        body: [ 'hello ', 'there' ]
    };
};

var jsgi = require('jsgi').start(app);
