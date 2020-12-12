<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class ViewServiceProvider
 * @package App\Providers
 */
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register directive for blade.
        $this->extendBladeJsData();
        $this->extendBladeValue();
    }

    /**
     * Register directive for blade.
     */
    protected function extendBladeJsData()
    {
        // JS data.
        Blade::directive(
            'jsdata',
            function ($expression) {
                /**
                 * @lang text
                 */
                return "<script> var storage = <?php echo json_encode({$expression}); ?>; </script>";
            }
        );
    }

    /**
     * Register directive for blade.
     * @uses @value($name, &$set, $default = null)
     */
    protected function extendBladeValue()
    {
        // Val directive.
        Blade::directive(
            'value',
            function ($expression) {
                $params = array_map('trim', explode(',', $expression));

                // List params.
                $name = $params[0];
                $set = $params[1] ?? 'null';
                $default = $params[2] ?? '""';

                /**
                 * @lang text
                 */
                return "<?php echo e(old({$name}, {$set} ?? {$default})); ?>";
            }
        );
    }
}
