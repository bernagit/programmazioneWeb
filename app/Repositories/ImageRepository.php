<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use Psy\Readline\Hoa\Console;
use \Ramsey\Uuid\Uuid as Uuid;
use \Exception as Exception;

class ImageRepository
{
    public function uploadImage($image)
    {
        return $this->storeImage($image);
    }

    private function storeImage($image)
    {
        try {
            // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $name = Uuid::uuid4()->toString();
            $path = $name . '.' . $image->extension();
            // $out->writeln($path);
            $res = Storage::disk('public')->putFileAs('images', $image, $path);
            // $out->writeln($res);
            return $path;
        } catch (Exception $e) {
            return null;
        }
    }

    public function uploadNullImage()
    {
        return 'default.png';
    }
}