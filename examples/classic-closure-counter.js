function make_counter (n) {

    var ctr = function () {
        ++n;
        return n;
    };
    return ctr;
}

var c = make_counter(15);
var d = make_counter(7);

print(c() + "\t" + d() + "\n");
print(c() + "\t" + d() + "\n");
print(c() + "\t" + d() + "\n");
print(c() + "\t" + d() + "\n");
