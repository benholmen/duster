<?php

it('fixes controller class order', function ($input, $expected) {
    $config = __DIR__ . '/.php-cs-fixer.php';
    $file = self::STUBS_DIR . '/' . md5($input) . '.php';
    file_put_contents($file, $input);

    exec("php ./vendor/bin/php-cs-fixer -q fix {$file} --config={$config}");

    $this->assertSame($expected, file_get_contents($file));
})->with([
    [
        <<<'EOT'
<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public const EXAMPLE = true;

    public function edit(Request $request){}

    public function store(Request $request){}

    public function index(Request $request){}

    public function update(Request $request){}

    public function destroy(Request $request){}

    public function create(Request $request){}

    public function show(Request $request){}
}
EOT,
        <<<'EOT'
<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public const EXAMPLE = true;

    public function index(Request $request){}

    public function create(Request $request){}

    public function store(Request $request){}

    public function show(Request $request){}

    public function edit(Request $request){}

    public function update(Request $request){}

    public function destroy(Request $request){}
}
EOT,
    ],
    [
        <<<'EOT'
<?php

namespace App\Http\Controllers;

class Example extends Something
{
    public const EXAMPLE = true;

    public function edit(Request $request){}

    public function store(Request $request){}
}
EOT,
        <<<'EOT'
<?php

namespace App\Http\Controllers;

class Example extends Something
{
    public const EXAMPLE = true;

    public function edit(Request $request){}

    public function store(Request $request){}
}
EOT,
    ],
    [
        <<<'EOT'
<?php

namespace Some\Other\Namespace;

class Example extends Controller
{
    public const EXAMPLE = true;

    public function edit(Request $request){}

    public function store(Request $request){}
}
EOT,
        <<<'EOT'
<?php

namespace Some\Other\Namespace;

class Example extends Controller
{
    public const EXAMPLE = true;

    public function edit(Request $request){}

    public function store(Request $request){}
}
EOT,
    ],
]);
