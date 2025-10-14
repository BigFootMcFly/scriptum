# DO NOT USE PREFETCHING!!!! It does not work well with the Viewing Mode Button... :(
    `->spa(hasPrefetching: false)`

# SPA does not work well with the Add New Note Button, do not use it for now...
```
    //NOTE: does not work, still got errors:
        Uncaught (in promise) Component not found: nU0zJagSrTqWRRFpWCYv
        Uncaught Component not found: nU0zJagSrTqWRRFpWCYv
    ->spa()
    ->spaUrlExceptions([
        url('/user/notes*'),
    ])
```