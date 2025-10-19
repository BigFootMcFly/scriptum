#
![BADGE](https://proxima.goliath.hu/scriptum/scriptum/actions/workflows/testing.yaml/badge.svg?branch=dev)

# Scriptum
To save quick notes for the future.<br>

> *So i don't need to remember them...*

> **NOTE:** this is a for testing/personal use, is is **NOT** production ready!<br>
Feel free to test it, read the source for ideas or solutions, deploy in your own lab or even use the publicly available version (*see bellow*).

# Usage

> For a fully functional demo see: [scriptum.goliath.hu](https://scriptum.goliath.hu/)

### Visitor

- You can browse/search all public notes by all users as a `guest` without registration.

### Registered user
- You need to login/register to be able to take notes. (top right icon)
- You can enable/disable **Multi Factor Authentication** on your [profile](/edit-profile) page.
- Both `email` and `authenticator app` can be used for MFA.
- You can set your "Viewing Mode" (top left icon)
    - **Private mode**: (*green)* you only see your own notes ("*single player*" mode)
    - **Public mode**: (*brown*) you can see all public notes from others beside your own notes ("*multi player*" mode)
- You can share your notes with others by marking them **public**.
- You can manage your own notes by pressing the "**Manage My Notes**" button.
- You can get back to the front page by pressing the "**Main**" button.
- You can search all notes with the search bar (***SHIFT+CTRL+F***)
- You can add a new note by pressing the "**+**" button on the top right or by pressing "***SHIFT+CTRL+N***"
- You can edit your own notes by pressing the "***edit note***" icon on the top right of the note header
- You can share your public note by copying the "***permalink***" link on the bottom right of the note
- You can see all public notes of a specified user by clicking the users name on top of a note

### Admin panel
> NOTE: the admin panel always requires **Multy Factor Authentication**

- Manage Users
- Manage Notes
- See fancy dashbord *(*no widgets yet*)
- The admin is like **superman**! If you revoke the admin right from your ***only*** admin user, then good luck :)

# Install

- Copy the **deploy** folder to your docker host, edit the compose file as needed

- Copy the [env.example](env.example) into the [volumes/env/](deploy/volumes/env) folder as "**.env**"
    ```bash
        cp env.example volumes/env/.env
    ```

- Fill in the variables in the **.env** file

    - replace all variables marked with "*# replace in production*"
    - fill in your own values marked by "*# fill in in production*"
    - Check the values
    - Double check them to be sure

- Create the database if it does not exist jet:
    ```bash
        touch volumes/database/database.sqlite
    ```

- In the folder wher the [compose](deploy/docker-compose.yaml) file is run:
    ```bash
        docker compose up
    ```

- Read logs for any error and correct them if they appear

- If all works, stop the container and bring it up detached mode
    ```bash
        docker compose up -d
    ```
- Use it

> NOTE: <br>
> The main page is accessible by the `DOMAIN` variable. <br>
> The admin panel is accessible by the `ADMIN_DOMAIN` variable. <br>
> Use `ADMIN_HASHED_PASSWORD` variable instead of `ADMIN_PASSWORD`. <br>
> To create a hashed password use: `htpasswd -n -B -C12 "" | cut -c 2-`

# Why

- Wanted to test [sqlite Full Text Search](https://sqlite.org/fts5.html) capabilities
- Needed to replace my current "*temporary*" note taking app, (which started as a testing playground to test the new features that came with laravel 8) and would not run on **php 8.4**
- To test, how felxible and hackable [filament](https://github.com/filamentphp/filament) v4+ (currently 4.1) is
- To get a deeper understanding of the inner working of filament
- To get a look inside the core of [alpine.js](https://alpinejs.dev/)
- To check out [TipTap](https://tiptap.dev/) editor

# Conclusions

- Filament is a great admin panel tool, with some degree of free customization
- It is not a general **UI** framework, use it for what it was intended
- Not recommending to use as a general part of a page, use as a separate admin panel.

# Technologies used

- [PHP 8.4+](https://www.php.net/releases/8.4/en.php)
- [Composer](https://getcomposer.org/)
- [Laravel 12+](https://laravel.com/)
- [Filament v4+](https://filamentphp.com/), *(current: [v4.1](https://filamentphp.com/content/danharrin-filament-v4-1))*
- [LiveWire](https://livewire.laravel.com/)
- [Alpine.js v3+](https://alpinejs.dev/)
- [tailwind](https://tailwindcss.com/)
- [Git](https://git-scm.com/)
- [GitHub](https://github.com/)
- [Gitea](https://about.gitea.com/)
- [Github workfolws](https://docs.github.com/en/actions/concepts/workflows-and-actions/workflows)
- [Docker](https://www.docker.com/) with [compose](https://docs.docker.com/compose/)
- [nginx](https://nginx.org/en/)
- [bash](https://www.gnu.org/software/bash/bash.html)
- [supervisord](https://supervisord.org/)
- [Cloudflare tunnel](https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/)
- [Visual Studio Code](https://code.visualstudio.com/)

# Maintance

- `php artisan down` - Enable maintaince mode
- `php artisan up` - Disable maintaince mode
- `php artisan fts:rebuild` - Rebuild the FTS5 index for the notes table