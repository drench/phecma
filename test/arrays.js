var a = ['b', 'c', 'd'];
a[3] = 'e';
a.push('a');

print(a.length + a.sort().join(','));
