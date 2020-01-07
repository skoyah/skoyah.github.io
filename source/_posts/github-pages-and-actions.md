---
extends: _layouts.post
section: content
title: Trigger Github Pages with Actions
date: 2019-01-08
description: Using tokens to authorize GitHub Pages building process with automated scripts.
cover_image: /assets/img/processing-data.png
featured: true
---

In the [last post](https://skoyah.github.io/blog/new-year-new-goals/) I briefly wrote about getting started this new year with creating my own blog. For that, I’m using the [GitHub Pages](https://pages.github.com/) to host the blog since is a free service.

<div class="flex justify-center">
    <blockquote class="twitter-tweet" data-conversation="none"><p lang="en" dir="ltr">Published! You can read it at <a href="https://t.co/3J783VDbui">https://t.co/3J783VDbui</a></p>&mdash; James Brooks (@jbrooksuk) <a href="https://twitter.com/jbrooksuk/status/1212397225843793920?ref_src=twsrc%5Etfw">January 1, 2020</a></blockquote> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>

This tweet from James Brooks introduced me to <a href="https://jigsaw.tighten.co/" target="_blank">Jigsaw</a>, a static site generator, and <a href="https://github.com/features/actions" target="_blank">GitHub Actions</a> to automate the deployment process.

I followed the GitHub workflow configuration setup from James but ran into some issues where the website build was failing even though the files were being correctly committed to the `master` branch.

It turns out that <a href="https://github.community/t5/GitHub-Actions/Github-action-not-triggering-gh-pages-upon-push/td-p/26869" target="_blank">git commands used in actions do not trigger the build process</a>. The token used by Github Actions - `GITHUB_TOKEN` - is not enough to start the build.

In order to initialize a new build everytime you push to the `master` (or `gh-pages`) branch you need some extra steps.

- First, you have to <a href="https://help.github.com/en/github/authenticating-to-github/creating-a-personal-access-token-for-the-command-line)" target="_blank">create a personal access token</a> (**PAT**) that will be used as the new token by your workflow.

- Second, you must <a href="https://help.github.com/en/actions/automating-your-workflow-with-github-actions/creating-and-using-encrypted-secrets" target="_blank">create a new secret for your repo</a> that will contain the PAT value.

- Finally, you need to reference the secret in the workflow config file.

As an example, here is the final workflow file after creating up a repo secret named `GH_PAGES_TOKEN`:

```yaml
name: Build & Publish

on:
  push:
    branches:
      - source
  schedule:
    - cron: "0 0 * * 1-5"

jobs:
  build-site:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
        with:
          token: ${{ secrets.GH_PAGES_TOKEN }}
      - name: Install Composer Dependencies
        run: composer install --no-ansi --no-interaction --no-scripts --no-suggest --no-progress --prefer-dist
      - name: Install NPM Dependencies
        run: npm install
      - name: Build Site
        run: npm run production
      - name: Stage Files
        run: git add -f build_production
      - name: Commit files
        run: |
          git config --local user.email "actions@github.com"
          git config --local user.name "GitHub Actions"
          git commit -m "Build for deploy"
      - name: Publish
        run: |
          git subtree split --prefix build_production -b master
          git push -f origin master:master
          git branch -D master
```

And we're done! Now everytime you commit your work to your develop branch, the automated deployment action will successfully trigger the GitHub Pages building process.
