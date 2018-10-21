# Local naming scheme

This is not absolute, it's more to create a surtain consistency when we get many transforms etc.

## Please keep to the naming scheme!
> mdnt.{category}.{type}.{source}.{action}

* **{category} examples:**
  * **business** - All company related stuff
  * **individual** - Thing that has to do with private individuals
  * **networking** - All things related to networking
  * **password** - Things to test for md5,sha1,dicionary hits and so on
  * **vehicle** - Could be looking up number plates etc, specs and so on
  * **examples** - The playground for testing and debug
  
* **{type} possibilities:**
  * **ext** - Lookup from external data sources
  * **tor** - TOR information
  * **net** - IP/Network information
  * **www** - Crawling things on the interwebs :)
  * **tst** - Testing (Mostly for debug!)
  
* **{source}:** A short name describing the source e.g website name or name of data place.

* **{action}:** I want this to be like desribing what we are doing like "lookupid" or "getasnumber" and so on...


# Transform Settings

This is a config block of settings that will allow the help command mdt-create-mtz script to build the import/export mtz file for Maltego.
This block must be located within the first 15 lines of the local transform file.

## Settings block

```php
/**
 * ***
 * Name.......: [MDNT] Example: Resolve IP
 * Description: This network example will resolve a single IP into a DNS hostname
 *
 * Active.....: True
 **/
```

**Active can be either:** Yes,True,On or No,False,Off

Case sensitivity does not matter.
