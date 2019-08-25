# blog-php-fpm
The code I wrote following https://ilovephp.jondh.me.uk/en/tutorial/make-your-own-blog with a few edits

1. I used OCI containers to set up an nginx, php-fpm, and mariadb server, with podman as the container engine.
I used buildah to set up the images, and uploaded them to a public quay.io repository. I then wrote a script (bin/init)
to pull down these images and set up the containers, as well as create a few directories that aren't part of the repo
(for persistent logs and mariadb data).

2. The tutorial uses SQLite, while I am using a mariadb database, so the code for connecting to the database is different,
as well as some of the SQL querys for initializing it. There is also no need to delete the SQLite database before reinstalling.

3. I've got a few different lines of code for some best practices I know of from https://phpbestpractices.org/ that have not 
been used by the author, such as using `htmlentities` with `ENT_QUOTES` instead of `htmlspecialchars` with `ENT_HTML5`. I 
should stress that the tutorial seems to be pretty well compliant with best practices according to my limited knowledge, just
a few small things here and there I swapped out is all.

If you wish to run this yourself for whatever reason:
1. Clone the repository: `git clone https://github.com/leggettc18/blog-php-fpm`
2. Enter the directory: `cd blog-php-fpm`
3. Run the initialization script `bin/init`
4. Go to `http://localhost:8080/install.php` in your browser
5. Click the link to go back to the Home Page.

Obviously not a whole lot of functionality here, this is something I'm going through for learning purposes.
