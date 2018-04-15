# Websocket client-server testing

see https://github.com/ratchetphp/Ratchet/issues/626

import mysql dump `example-db.sql`


### Start Websocket server and start Websocket emitter
```bash
# ws server
bin/ws-server

# ws emitters
bin/ws-emitter-timer
# or
bin/ws-emitter-simple
```


### Or you can start single Websocket server and Emitter (connect into websocket protocol)
```bash
bin/ws-single-server-emitter-timer
```


### Or you can start joint Websocket server and Emitter (recommended solution)
```bash
bin/ws-joint-server-emitter-timer
```


open `public/index.html` into browser


for auth you can use https://php.net/hash_hmac
