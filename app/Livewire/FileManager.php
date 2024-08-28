<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class FileManager extends Component
{
    public $currentPath = ''; // Holds the current directory path
    public $files = [];

    public function mount($initialPath = null)
    {
        $this->currentPath = $initialPath ?: base_path(); // Start with base path or provided initial path
        $this->files = $this->getDirectoryTree($this->currentPath);
    }

    // Function to recursively build the directory tree
    function getDirectoryTree($directory)
    {
        $items = [];

        // Get all directories in the current directory
        foreach (File::directories($directory) as $dir) {
            $directoryInfo = new \SplFileInfo($dir);
            $items[] = [
                'type' => 'directory',
                'name' => $directoryInfo->getFilename(),
                'path' => $directoryInfo->getPathname(),
                'size' => $directoryInfo->getSize(),
                'last_modified' => $directoryInfo->getMTime(),
                // 'children' => $this->getDirectoryTree($dir), // Recursive call
            ];
        }

        // Get all files in the current directory
        foreach (File::files($directory) as $file) {
            $items[] = [
                'type' => 'file',
                'name' => $file->getFilename(),
                'path' => $file->getPathname(),
                'size' => $file->getSize(),
                'last_modified' => Carbon::createFromTimestamp($file->getMTime())->toDateTimeString(),
            ];
        }

        return $items;
    }
    // Method to load the contents of a directory when clicked
    public function loadDirectory($path)
    {
        $this->currentPath = $path;
        $this->files = $this->getDirectoryTree($path);
    }

    public function render()
    {
        return view('livewire.file-manager');
    }
}
