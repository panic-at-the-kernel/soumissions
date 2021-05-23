<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;

class DefaultController extends Controller
{

    public function home(Request $request, Response $response, array $args)
    {
        // Remove some limits
        ini_set("upload_max_filesize", "100M");
        ini_set("post_max_size", "100M");
        ini_set("memory_limit", -1);
        $msg = null;

        // Allowed mimetypes
        $allowed = [
            "application/x-bzip",
            "application/x-bzip2",
            "application/zip",
            "application/x-7z-compressed",
            "application/x-tar",
            "application/x-rar-compressed",
            "application/x-zip-compressed",
            "application/x-gzip"
        ];


        if ($request->getMethod() == "POST") {
            $files = $request->getUploadedFiles();
            if (!isset($files['file']) && !isset($request->getParsedBody()['github']) && !isset($request->getParsedBody()['firstname'], $request->getParsedBody()['lastname'], $request->getParsedBody()['category'], $request->getParsedBody()['rules'])) {
                // Fields are missing
                $msg = "error";
            } else {

                if (isset($files['file']) && $files['file']->getSize() > 0) {
                    // upload file
                    if ($files['file']->getError() !== UPLOAD_ERR_OK || !in_array($files['file']->getClientMediaType(), $allowed)) {
                        // Unable to upload file or incorrect type
                        $msg = "error";
                    } else {

                        // Save submission
                        $id = $this->newSub($request->getParsedBody());
                        $extension = pathinfo($files['file']->getClientFilename(), PATHINFO_EXTENSION);

                        $files['file']->moveTo(__DIR__ . "/../../submissions/{$id}/file." . $extension);

                        $msg = "success";
                    }
                } else if (isset($request->getParsedBody()['github']) && !empty($request->getParsedBody()['github'])) {
                    // dl: https://github.com/panic-at-the-kernel/discord-bot-challenge/archive/refs/heads/main.zip

                    if (!preg_match("/(https?:\/\/github.com\/[a-zA-Z0-9-]*\/[a-zA-Z0-9-]*\/?)/", $request->getParsedBody()['github'])) {
                        // Github url is not starting with https?://github.com or it is not a valid repository url
                        $msg = "error";
                    } else {

                        // Save submission
                        $id = $this->newSub($request->getParsedBody());

                        // Download and save main branch zip
                        $content = file_get_contents(rtrim($request->getParsedBody()['github'], "/") . "/archive/refs/heads/main.zip");

                        if ($content === false) {
                            $msg = "error";
                        } else {
                            if (file_put_contents(__DIR__ . "/../../submissions/{$id}/file.zip", $content) === false) {
                                $msg = "error";
                            } else {
                                $msg = "success";
                            }
                        }
                    }

                } else {
                    $msg = "error";
                }
            }
        }

        return $response->write($this->getContainer()->get("twig")->render('form.twig', [
            "msg" => $msg,
            "allowed" => implode(",", $allowed),
        ]));
    }

    public function newSub($body)
    {
        $id = uniqid("S");
        // Create folders if missing
        if (!file_exists(__DIR__ . "/../../submissions")) {
            mkdir(__DIR__ . "/../../submissions", 0777);
        }
        if (!file_exists(__DIR__ . "/../../submissions/{$id}")) {
            mkdir(__DIR__ . "/../../submissions/{$id}", 0777);
        }

        // Add a info.txt file with the submission details
        file_put_contents(__DIR__ . "/../../submissions/{$id}/info.txt", "Prénom: {$body['firstname']}\nNom: {$body['lastname']}\nCatégorie: {$body['category']}\nGithub: {$body['github']}\nNote:\n{$body['additional']}\nDate: " . date('d/m/Y H:i:s') . "\n");

        return $id;
    }
}
