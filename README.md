AmazonS3Viewer
==============

## Install

### Get the code

    git clone https://github.com/user/reposName.git

### Configure

To configure your Amazon S3 secret keys and Url, copy
`/app/config/parameters.yml.dist` to `/app/config/parameters.yml` and
edit it according to your amazon settings.

### Install vendors

    curl -s http://getcomposer.org/installer | php
    php composer.phar install

### Change Image Per Page

To change list of images per page opent for edit `/app/config/config.yml` and
change `items_per_page` under the `parameters` section