# Shortlink

A simple tool to generate short links and use them to redirect to a specific destination URL.

## How to use it?

The tool uses a standard Symfony console to manage links. Enter the command in the main directory 
``php bin/console short-url:<command>``

```text
short-url
  short-url:change-status         Changes the status of the selected shortened link
  short-url:delete                Deletes the short link with provided ID
  short-url:generate              Generates a short URL for provided destination URL
  short-url:list                  Displays a list of short links
```

## Redirecting

When an active link is found, a page with information about the ongoing redirection is displayed for 5 seconds. 
You can edit it ``templates/redirect.html.twig`` and, for example, add a reference to the tracking script.

## License

MIT
