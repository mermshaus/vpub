# Annotation Suite

_(…it’s a working title)_

A suite of tools to build and utilize a database of metadata connected to file
contents using hashes of the content for identification and a HTTP API for
communication.

## Introduction

Let’s say you have a file `dog-in-spain.jpg`. The byte content of the file is
probably never going to change. If you apply a hash function like SHA256 to the
file’s byte content, you’ll always get the same result, even if you rename the
file or move it to a different directory or even a different machine.

You’d like to add some metadata to the image. For instance, you might want to
tag it with `dog` and `spain` and maybe `vacation 2019`. You could use a
specific metadata format like Exif and add the metadata directly to the file.
You could also use a photo collection software that allows you to import images
and to add metadata in a way that’s managed by and probably specific to the
application. Or you could use a generic solution that lets you annotate a file’s
content (instead of eg. connecting the metadata to a location in the file
system) with arbitrary metadata without touching the file itself.

If the last option sounds good to you, this project might be for you.

## Prerequisites

The project is in prototype state right now. Most of the existing code is
written in PHP.

To run any of it, you’ll need:

- A Linux system (some paths are hardcoded, sorry)
- PHP 7.4
- Composer

## Setup

For each of the applications in the `apps` directory, `cd` into the directory
and run `composer install`.

Go back to the project’s root directory and run `scripts/start_server.sh` to
start a local annotation server at `localhost:8080`.

From a different terminal, run `apps/client/app.php list` to see a list of
commands you can use to communicate with the server.

Find eg. an image file and try to set an annotation for it.

Right now, all data will be stored in `~/.local/share/vpub` in JSON format. This
will be replaced by a more sophisticated solution in the future (probably
SQLite).

Please remember that all parts of the project are in prototype state. So don’t
expect anything to be ready for real use. You can build simple things with it (I
actually do), but don’t expect too much. Have fun experimenting with stuff. I’d
love to hear from you.
