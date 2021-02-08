<?php namespace Konstruktiv\VueI18nGenerator\Commands;

use Illuminate\Console\Command;

use Konstruktiv\VueI18nGenerator\Generator;

class GenerateInclude extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vue-i18n:generate {--umd} {--multi} {--with-vendor} {--file-name=} {--lang-files=} {--multi-locales}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generates a vue-i18n|vuex-i18n compatible js array out of project translations";

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $root = base_path() . config('vue-i18n-generator.langPath');
        $config = config('vue-i18n-generator');

        // options
        $umd = $this->option('umd');
        $multipleFiles = $this->option('multi');
        $withVendor = $this->option('with-vendor');
        $fileName = $this->option('file-name');
        $langFiles = $this->option('lang-files');
        $multipleLocales = $this->option('multi-locales');

        if ($multipleFiles || $multipleLocales) {
            $files = (new Generator($config))
                ->generateMultiple($root, $multipleLocales);

            if ($config['showOutputMessages']) {
                $this->info("Written to : " . $files);
            }

            return;
        }

        if ($langFiles) {
            $langFiles = explode(',', $langFiles);
        }

        $data = (new Generator($config))
            ->generateFromPath($root, $withVendor, $langFiles);


        $jsFile = $this->getFileName($fileName);
        file_put_contents($jsFile, $data);

        if ($config['showOutputMessages']) {
            $this->info("Written to : " . $jsFile);
        }
    }

    /**
     * @param string $fileNameOption
     * @return string
     */
    private function getFileName($fileNameOption)
    {
        if (isset($fileNameOption)) {
            return base_path() . $fileNameOption;
        }

        return base_path() . config('vue-i18n-generator.jsFile');
    }
}
