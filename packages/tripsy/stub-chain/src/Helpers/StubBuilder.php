<?php

namespace Tripsy\StubChain\Helpers;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class StubBuilder
{
    /**
     * The filesystem instance.
     */
    private Filesystem $fileSystem;

    /**
     * Reserved names that cannot be used for generation.
     *
     * @var string[]
     */
    private array $reservedNames = [
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
    private string $stub;

    /**
     * Extension for stub file
     * `nested` is a special case - parent related vars are injected for it
     */
    private string $extension;

    /**
     * Stub content
     */
    private string $stubContent;

    /**
     * Associative array with values to be replaced in stub content
     * The array is updated using `addStubData` method
     */
    private array $stubData;

    /**
     * Flag used to determine if the file is overwritten if already exist
     */
    private bool $overwrite = false;

    /**
     * Flag used to determine if the file is auto added to git
     */
    private bool $gitAdd = false;

    /**
     * Flag used to determine if the message is displayed when file already exist & overwrite is false
     */
    private bool $silence = true;

    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * Get the command argument value based on key
     */
    public function getArgumentValue(string $value): string
    {
        return strtolower(trim($value));
    }

    /**
     * Return the command option based on key as a bool
     */
    public function getOptionAsBoolean(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Set stub argument
     */
    public function setStubArgument(string $stubArgument): void
    {
        $stub = $this->getArgumentValue($stubArgument); //ex: model-cache OR api-model-controller.nested OR model.custom.project

        $stubParts = explode('.', $stub);

        if (count($stubParts) > 1) {
            array_shift($stubParts); //first element is stub name

            $this->extension = implode('.', $stubParts);
        }

        $this->stub = $stub;
    }

    /**
     * Get stub argument
     *
     * @throws Exception
     */
    public function getStubArgument(bool $excludeExtension = false): string
    {
        if (isset($this->stub) === false) {
            throw new Exception(__('stub-chain::stub-chain.stub_argument_not_set'));
        }

        if ($excludeExtension === true && isset($this->extension) === true) {
            return str_replace('.'.$this->extension, '', $this->stub);
        }

        return $this->stub;
    }

    /**
     * Return `true` if stub extension matches
     */
    public function hasExtension(string $extension, bool $exactMatch = false): bool
    {
        if (isset($this->extension) === false) {
            return false;
        }

        if ($exactMatch === true) {
            return $this->extension === $extension;
        }

        return in_array($extension, explode('.', $this->extension));
    }

    /**
     * Build class name based on stub file name
     * Provided `args` keys are replaced with corresponding values
     *
     * @throws Exception
     */
    public function buildClassName(...$args): string
    {
        $classNameParts = explode('-', $this->getStubArgument(true));

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
    public function isReservedName(string $name): bool
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
    private function getStubContent(): string
    {
        if (isset($this->stubContent) === false) {
            $this->stubContent = file_get_contents($this->getStubPath());
        }

        return $this->stubContent;
    }

    /**
     * @throws FileNotFoundException
     * @throws Exception
     */
    private function getStubPath(): string
    {
        $stubFile = $this->getStubArgument().'.stub';
        $stubPath = base_path('/stubs').'/tripsy/'.$stubFile;

        if ($this->fileExists($stubPath) === true) {
            return $stubPath;
        }

        $stubPath = config('stub-chain.stubs_path').'/'.$this->getStubArgument().'.stub';

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
    public function addStubData(string $key, string $value): void
    {
        $this->stubData[$key] = $value;
    }

    /**
     * Determine destination file name
     */
    public function determineDestinationFileName(string $className): void
    {
        $this->destinationFileName = $className.'.php';
    }

    /**
     * @throws Exception
     */
    public function getDestinationFileName(): string
    {
        if (isset($this->destinationFileName)) {
            return $this->destinationFileName;
        }

        throw new Exception(__('stub-chain::stub-chain.destination_file_name_not_determined'));
    }

    /**
     * Determine destination file folder
     *
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function determineDestinationFileFolder(string $model): void
    {
        $namespace = $this->extractNamespaceFromStubContent($this->getStubContent());

        // Replace stub data
        $namespace = strtr($namespace, $this->getStubData());

        $fileFolder = $this->convertNamespaceToFolder($namespace);

        $this->destinationFileFolder = base_path($fileFolder);
    }

    /**
     * @throws Exception
     */
    public function getDestinationFileFolder(): string
    {
        if (isset($this->destinationFileFolder)) {
            return $this->destinationFileFolder;
        }

        throw new Exception(__('stub-chain::stub-chain.destination_file_folder_not_determined'));
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
     * Setter for option --overwrite
     *
     * Note: When overwrite is true existing files will be overwritten
     */
    public function setOverwrite(string $value): void
    {
        $this->overwrite = $this->getOptionAsBoolean($value);
    }

    /**
     * Setter for option --gitAdd
     *
     * Note: When value is true generated files will be staged for commit
     */
    public function setGitAdd(string $value): void
    {
        $this->gitAdd = $this->getOptionAsBoolean($value);
    }

    /**
     * Setter for option --silence
     *
     * Note:
     *      When value is true & overwrite is false no message will be displayed for existing file.
     *      This is mostly an output control and doesn't affect functionality in any way
     */
    public function setSilence(string $value): void
    {
        $this->silence = $this->getOptionAsBoolean($value);
    }

    /**
     * Generate destination file with content build based on defined `stub` content
     * in which `stubData` values have been replaced.
     *
     * Destination folder will be created if it doesn't exist
     *
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function generate(): array
    {
        $fileContent = $this->buildFileContent();

        $filePath = $this->getDestinationFileFolder().'/'.$this->getDestinationFileName();

        if ($this->fileExists($filePath) === true) {
            if ($this->overwrite === true) {
                $this->fileSystem->put($filePath, $fileContent);

                if ($this->gitAdd === true) {
                    $this->gitStageForCommit($filePath);
                }

                return [
                    'response' => 'warn',
                    'count' => 'overwritten',
                    'message' => __('stub-chain::stub-chain.file_overwritten', [
                        'fileName' => $this->getDestinationFileName(),
                        'fileFolder' => $this->getDestinationFileFolder(),
                        'stub' => $this->getStubArgument(),
                    ]),
                ];
            } else {
                if ($this->silence === false) {
                    return [
                        'response' => 'warn',
                        'count' => 'skipped',
                        'message' => __('stub-chain::stub-chain.file_already_exist', [
                            'fileName' => $this->getDestinationFileName(),
                            'fileFolder' => $this->getDestinationFileFolder(),
                            'stub' => $this->getStubArgument(),
                        ]),
                    ];
                } else {
                    return [
                        'response' => 'skip',
                        'count' => 'skipped',
                    ];
                }

            }
        } else {
            $this->makeDirectory($filePath);

            $this->fileSystem->put($filePath, $fileContent);

            if ($this->gitAdd === true) {
                $this->gitStageForCommit($filePath);
            }

            return [
                'response' => 'info',
                'count' => 'generated',
                'message' => __('stub-chain::stub-chain.file_generated', [
                    'fileName' => $this->getDestinationFileName(),
                    'fileFolder' => $this->getDestinationFileFolder(),
                    'stub' => $this->getStubArgument(),
                ]),
            ];

        }
    }

    /**
     * Stage file for commit
     */
    private function gitStageForCommit(string $filePath): void
    {
        Process::run('git add '.$filePath);
    }

    /**
     * Replace `stubData` in `stub` content
     *
     * @throws FileNotFoundException
     */
    private function buildFileContent(): string
    {
        return strtr($this->getStubContent(), $this->prepareStubData());
    }

    /**
     * `stubData` is an associate array;
     *  This method replace key with the pattern used in stub content file (eg: {{ $value }}
     */
    private function prepareStubData(): array
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
    public function getStubData(): array
    {
        return $this->stubData;
    }

    /**
     * Get an array list with related stub files - extracted based on "use" & "extra" dynamic classes
     *
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function getRelatedStubFiles(): array
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
     *
     * @throws Exception
     */
    private function determineStubFilesForRelatedDynamicClasses(array $relatedClasses): array
    {
        // Config var
        $classNeedle = '{{ $model }}';

        // Return only classes which contain specific needle
        $dynamicClasses = array_filter($relatedClasses, function ($v) use ($classNeedle) {
            return str_contains($v, $classNeedle);
        });

        // Transform name for used class (eg: {{ $model }}Delete
        return array_map(function ($v) use ($classNeedle) {
            // $v ~ App\Commands\{{ $model }}DeleteCommand
            $parts = explode('\\', $v);

            $dynamicClass = end($parts); // {{ $model }}DeleteCommand::custom.extension

            $dynamicClassParts = explode('::', $dynamicClass);

            switch (count($dynamicClassParts)) {
                case 1:
                    $extension = null;
                    break;
                case 2:
                    $dynamicClass = array_shift($dynamicClassParts); // First part is class
                    $extension = $dynamicClassParts[0];
                    break;
                default:
                    throw new Exception(__('stub-chain::stub-chain.invalid_class_extension', [
                        'class' => $dynamicClass,
                    ]));
            }

            $replaceNeedleInClass = str_replace($classNeedle, 'Model', $dynamicClass); // ModelDeleteCommand

            $stub = Str::snake($replaceNeedleInClass, '-'); // model-delete-command

            if (empty($extension) === false) {
                $stub .= '.'.$extension;
            }

            return $stub;
        }, $dynamicClasses);
    }

    /**
     * Determine if the file exists.
     */
    private function fileExists(string $filePath): bool
    {
        return $this->fileSystem->exists($filePath);
    }

    /**
     * Build the directory for the class if necessary.
     */
    private function makeDirectory(string $path): void
    {
        if ( ! $this->fileSystem->isDirectory(dirname($path))) {
            $this->fileSystem->makeDirectory(dirname($path), 0777, true, true);
        }
    }
}
