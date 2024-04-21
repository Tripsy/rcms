<?php

namespace Tripsy\StubChain\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

abstract class StubCommand extends Command implements PromptsForMissingInput
{
    /**
     * The filesystem instance.
     */
    protected Filesystem $fileSystem;

    /**
     * Reserved names that cannot be used for generation.
     *
     * @var string[]
     */
    protected array $reservedNames = [
        '__halt_compiler',
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'enum',
        'eval',
        'exit',
        'extends',
        'false',
        'final',
        'finally',
        'fn',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'match',
        'namespace',
        'new',
        'or',
        'parent',
        'print',
        'private',
        'protected',
        'public',
        'readonly',
        'require',
        'require_once',
        'return',
        'self',
        'static',
        'switch',
        'throw',
        'trait',
        'true',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
        'yield',
        '__CLASS__',
        '__DIR__',
        '__FILE__',
        '__FUNCTION__',
        '__LINE__',
        '__METHOD__',
        '__NAMESPACE__',
        '__TRAIT__',
    ];

    /**
     * Value for determined destination file folder absolute path
     */
    protected string $destinationFileFolder;

    /**
     * Value for determined destination file name
     */
    protected string $destinationFileName;

    /**
     * Stub key (eg: file name) provided as command argument
     */
    protected string $stub;

    /**
     * Stub content
     */
    protected string $stubContent;

    /**
     * Associative array with values to be replaced in stub content
     * The array is updated using `addStubData` method
     */
    protected array $stubData;

    public function __construct(Filesystem $fileSystem)
    {
        parent::__construct();

        $this->fileSystem = $fileSystem;
    }

    /**
     * Get the command argument value based on key
     */
    final protected function getArgumentValue(string $key): string
    {
        return strtolower(trim($this->argument($key)));
    }

    /**
     * Return the command option based on key as a bool
     */
    final protected function getOption(string $key): bool
    {
        return filter_var($this->option($key), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set stub name
     */
    final protected function setStubName(string $value): void
    {
        $this->stub = $value;
    }

    /**
     * Build class name based on stub file name
     * Provided `args` keys are replaced with corresponding values
     */
    final protected function buildClassName(...$args): string
    {
        $classNameParts = explode('-', $this->stub);

        foreach ($classNameParts as &$v) {
            if (empty($args[$v]) === false) {
                $v = $args[$v];
            } else {
                $v = ucfirst($v);
            }
        }

        return ucfirst(implode('', $classNameParts));
    }

    /**
     * Checks whether the given name is reserved.
     */
    final protected function isReservedName(string $name): bool
    {
        return in_array(
            strtolower($name),
            collect($this->reservedNames)
                ->transform(fn ($name) => strtolower($name))
                ->all()
        );
    }

    /**
     * Get stub file content
     *
     * @throws FileNotFoundException
     */
    final protected function getStubContent(): string
    {
        if (isset($this->stubContent) === false) {
            $this->stubContent = file_get_contents($this->getStubPath());
        }

        return $this->stubContent;
    }

    /**
     * @throws FileNotFoundException
     */
    final protected function getStubPath(): string
    {
        $stubFile = $this->stub.'.stub';
        $stubPath = base_path('/stubs').'/'.$stubFile;

        if ($this->fileExists($stubPath) === true) {
            return $stubPath;
        }

        $stubPath = config('stub-chain.stubs_path').'/'.$this->stub.'.stub';

        if ($this->fileExists($stubPath) === true) {
            return $stubPath;
        }

        throw new FileNotFoundException(__('stub-chain::stub-chain.stub_not_found', [
            'stub' => $stubFile,
        ]));
    }

    /**
     * Add key / value entry to array `stubData`
     */
    final protected function addStubData(string $key, string $value): void
    {
        $this->stubData[$key] = $value;
    }

    /**
     * Determine destination file name
     */
    protected function determineDestinationFileName(string $className): void
    {
        $this->destinationFileName = $className.'.php';
    }

    /**
     * Determine destination file folder
     *
     * @throws FileNotFoundException
     * @throws Exception
     */
    protected function determineDestinationFileFolder(string $model): string
    {
        $namespace = $this->extractNamespaceFromStubContent($this->getStubContent());

        // Replace model
        $namespace = str_replace('{{ $model }}', $model, $namespace);

        $fileFolder = $this->convertNamespaceToFolder($namespace);

        return base_path($fileFolder);
    }

    /**
     * Extract namespace from stub file content
     *
     * @throws FileNotFoundException
     * @throws Exception
     */
    private function extractNamespaceFromStubContent(string $content): string
    {
        // Regular expression pattern to match a namespace
        $pattern = '/namespace\s+(.+);/';

        // Perform the regular expression match
        if (preg_match($pattern, $content, $matches)) {
            // Get the captured namespace
            return $matches[1];
        } else {
            throw new Exception(__('stub-chain::stub-chain.namespace_not_found', [
                'stub' => $this->getStubPath(),
            ]));
        }
    }

    /**
     * Convert namespace to folder
     */
    private function convertNamespaceToFolder(string $namespace): string
    {
        // Regular expression pattern to match a namespace
        $folder = str_replace('\\', '/', $namespace);

        if (str_starts_with($folder, 'App/') === true) {
            $folder = str_replace('App/', 'app/', $folder);
        }

        return $folder;
    }

    /**
     * Generate destination file with content build based on defined `stub` content
     * in which `stubData` values have been replaced.
     *
     * Destination folder will be created if it doesn't exist
     *
     * Return false if file already exist
     *
     * @throws FileNotFoundException
     * @throws Exception
     */
    protected function generate(string $destinationFileFolder): bool
    {
        $fileContent = $this->buildFileContent();

        $filePath = $destinationFileFolder.'/'.$this->destinationFileName;

        if ($this->fileExists($filePath) === true) {
            return false;
            //            throw new Exception(__('stub-chain::stub-chain.file_already_exist', [
            //                'filePath' => $filePath,
            //            ]));
        } else {
            $this->makeDirectory($filePath);
        }

        $this->fileSystem->put($filePath, $fileContent);

        return true;
    }

    /**
     * Replace `stubData` in `stub` content
     *
     * @throws FileNotFoundException
     */
    final protected function buildFileContent(): string
    {
        return strtr($this->getStubContent(), $this->prepareStubData());
    }

    /**
     * `stubData` is an associate array;
     *  This method replace key with the pattern used in stub content file (eg: {{ $value }}
     */
    protected function prepareStubData(): array
    {
        $stubData = $this->getStubData();

        foreach ($stubData as $k => $v) {
            $stubData['{{ $'.$k.' }}'] = $v;

            unset($stubData[$k]);
        }

        return $stubData;
    }

    /**
     * Getter used to read `stubData`
     */
    final protected function getStubData(): array
    {
        return $this->stubData;
    }

    /**
     * Get an array list with related stub files - extracted based on "use" & "extra" dynamic classes
     *
     * @throws FileNotFoundException
     */
    protected function getRelatedStubFiles(): array
    {
        $relatedClasses = $this->extractRelatedClassesFromStubContent();

        return $this->determineStubFilesForRelatedDynamicClasses($relatedClasses);
    }

    /**
     * Extract used classes from stub file content
     *
     * @throws FileNotFoundException
     */
    private function extractRelatedClassesFromStubContent(): array
    {
        // Regular expression pattern to match used classes
        $pattern = '/(use|extra)\s+(.+);/';

        // Perform the regular expression match
        if (preg_match_all($pattern, $this->getStubContent(), $matches)) {
            // Get the captured namespace
            return $matches[2];
        }

        return [];
    }

    /**
     * Return array with stub file names corresponding the use & extra classes extracted from stub currently processed
     */
    private function determineStubFilesForRelatedDynamicClasses(array $usedClasses): array
    {
        $dynamicClassesNeedle = [
            'Model' => '{{ $model }}',
            'Parentmodel' => '{{ $parentModel }}',
        ];

        //return only classes which contain specific needle
        $dynamicClasses = array_filter($usedClasses, function ($v) use ($dynamicClassesNeedle) {
            foreach ($dynamicClassesNeedle as $needle) {
                if (str_contains($v, $needle) === true) {
                    return true;
                }
            }

            return false;
        });

        //transform name for used class (eg: {{ $model }}Delete
        return array_map(function ($v) use ($dynamicClassesNeedle) {
            // $v ~ App\Commands\{{ $model }}DeleteCommand
            $parts = explode('\\', $v);

            $dynamicClass = end($parts); // {{ $parentModel }}{{ $model }}DeleteCommand

            $replaceNeedleInClass = strtr($dynamicClass, array_flip($dynamicClassesNeedle)); // ParentmodelModelDeleteCommand

            $dashedString = Str::snake($replaceNeedleInClass, '-'); // parentmodel-model-delete-command

            return str_replace('parentmodel', 'parentModel', $dashedString); //parentModel-model-delete-command
        }, $dynamicClasses);
    }

    /**
     * Determine if the file exists.
     */
    final protected function fileExists(string $filePath): bool
    {
        return $this->fileSystem->exists($filePath);
    }

    /**
     * Build the directory for the class if necessary.
     */
    final protected function makeDirectory(string $path): string
    {
        if ( ! $this->fileSystem->isDirectory(dirname($path))) {
            $this->fileSystem->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }
}
