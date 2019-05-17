<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Image;
use App\CommentImage;
use App\Classes\TinyPNG;

\Tinify\setKey(env("TINY_PNG_API_KEY"));

class OptimiseImages extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimise-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimise images via Tiny PNG';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try {
            // Use the Tinify API client.

            // Get all unoptimised images
            $comment_images = DB::table('comment_images')->where('optimised','=', null)->get();;
            $images = DB::table('images')->where('optimised','=', null)->get();;

            // Set counter
            $num_optimised = 0;

            // Loop through comment images
            foreach($comment_images as $image) {
                
                // Get image
                $source = \Tinify\fromFile('/home/ryanshir/public_html/subdomains/itinerary/public/' . $image->path);
                // $source = \Tinify\fromUrl("https://itinerary.ryanshirley.ie/" . $image->path);

                // Resize and optimise
                $resized = $source->resize(array(
                    "method" => "scale",
                    "width" => 1500,
                ));

                // Save image
                $resized->toFile('/home/ryanshir/public_html/subdomains/itinerary/public/' . $image->path);
                // $resized->toFile('/home/ryanshir/public_html/subdomains/itinerary/public/' . $image->path);

                // Update as optimised
                CommentImage::where('id', $image->id)
                ->update(['optimised' => 1]);

                // Update counter
                $num_optimised++;
            }

            // Loop through comment images
            foreach($images as $image) {
                
                // Get image
                $source = \Tinify\fromFile('/home/ryanshir/public_html/subdomains/itinerary/public/' . $image->path);
                // $source = \Tinify\fromUrl("https://itinerary.ryanshirley.ie/" . $image->path);

                // Resize and optimise
                $resized = $source->resize(array(
                    "method" => "scale",
                    "width" => 1500,
                ));

                // Save image
                $resized->toFile('/home/ryanshir/public_html/subdomains/itinerary/public/' . $image->path);
                // $resized->toFile('/home/ryanshir/public_html/subdomains/itinerary/public/' . $image->path);

                // Update as optimised
                Image::where('id', $image->id)
                ->update(['optimised' => 1]);

                // Update counter
                $num_optimised++;
            }
    
            $this->info('There was ' . $num_optimised . ' images optimised.');

        } catch(\Tinify\AccountException $e) {
            $this->error(print("The error message is: " . $e->getMessage()));
            // Verify your API key and account limit.
        } catch(\Tinify\ClientException $e) {
            // Check your source image and request options.
            $this->error($e);
        } catch(\Tinify\ServerException $e) {
            // Temporary issue with the Tinify API.
            $this->error($e);
        } catch(\Tinify\ConnectionException $e) {
            // A network connection error occurred.
            $this->error($e);
        } catch(Exception $e) {
            // Something else went wrong, unrelated to the Tinify API.
            $this->error($e);
        }
    }
}