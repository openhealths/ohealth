<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->addNonemptyDirective();
        $this->addIconDirective();
    }

    protected function addNonemptyDirective(): void
    {
        Blade::directive('nonempty', static fn ($expression) => "<?php if(!empty($expression)): ?>");

        Blade::directive('elsenonempty', static fn () => "<?php else: ?>");

        Blade::directive('endnonempty', static fn () => "<?php endif; ?>");
    }

    /**
     * Expression will be something like: @icon('bell', 'w-6 h-6')
     *
     * @return void
     */
    protected function addIconDirective(): void
    {
        Blade::directive('icon', static function (string $expression) {
            return "<?php
                // Parse arguments from the directive
                \$iconArgs = [$expression];
                \$iconName = trim(\$iconArgs[0], \"'\\\"\");
                \$iconClass = \$iconArgs[1] ?? '';
                \$iconFile = resource_path('icons/' . \$iconName . '.svg');
                \$svgContent = file_exists(\$iconFile) ? file_get_contents(\$iconFile) : '';
                if (\$iconClass && \$svgContent) {
                    // Inject class attribute into SVG tag
                    \$svgContent = preg_replace(
                        '/<svg(.*?)(class=\".*?\")?(.*?)>/',
                        '<svg$1 class=\"' . e(\$iconClass) . '\"$3>',
                        \$svgContent
                    );
                }
                echo \$svgContent;
            ?>";
        });
    }
}
