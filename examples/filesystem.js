var fs = require('fs');

if (fs.isReadable('/etc/hosts')) {
    etchosts = fs.read('/etc/hosts');
    fs.open('/tmp/fstest', 'w').write("some stuff\n");
    fs.open('/tmp/fstest', 'a').write("more stuff\n");
    fs.copy('/etc/hosts', '/tmp/hosts');
}                                                                       
