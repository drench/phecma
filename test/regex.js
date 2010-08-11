var a = "These are some words";

var r = a.match(/^(these)\s+(\w+)\s+(.+)$/i);
print(r.join('|'));
