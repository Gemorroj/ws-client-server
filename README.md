# Weboscket client-server testing

see https://github.com/ratchetphp/Ratchet/issues/626

import mysql dump `example-db.sql`



Start Websocket server
```bash
bin/ws-server
```
Start Websocket emitter
```bash
bin/ws-emitter-timer
```

Or you can start single Websocket server and Emitter
```bash
bin/ws-single-server-emitter-timer
```



open `public/index.html` into browser


for auth you can use http://php.net/hash_hmac
