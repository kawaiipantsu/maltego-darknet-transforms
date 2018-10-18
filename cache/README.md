# Cache

## What's the meaning of this cache dir ?
Well ... when i was thinking about what i wanted to do, i pretty much had many ideas in my head. Some of them overlapping as they kept coming back like looking up DNS stuff and so on.

So i was thinking about creating a simple cache dir with a simple PHP class interface to do nothing more than "set" and "get" stuff. The structure should be something simple like how Redis does it but in a file structure in stead. I knonw it's I/O that might be unwanted for some so i'm also including a way to fully disable the cache part but the get/set functions will still work just not store anyting in the cache e.g - So yeah...

## What file structure where you thinking of?
I was thinking of something simple like Redis store it's objects in each database.
So i'm refereing to how Redis builds it's data objects.

Since we will have a consitent {type} in our Transform name i'm thinking about using that as one of the "key" names in our file names. 
  * Like: {type}.{identifier}.{hash} e.g net.ip.f528764d624db129b32c21fbca0cb8d6
    * {type} will be one of our predefined types
    * {identifier} would be something like "ip" when using type=net e.g
    * {hash} would be the hashed value of the IP in this example.
    
  With this naming convention we could even split it into directories etc.
  
  Anyhoo, this was just a thought on what i wanted to do with the cache feature.
  Nothing have been carved into stone yet!
