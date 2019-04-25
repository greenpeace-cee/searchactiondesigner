# Store search actions in your extension code.

It is possible to [export a search action](export_import.md) and store the code in your extension.

In your extension create a directory called `searchactions` and create a file for each action called `actionname.json`

The contents of the file could be copied from the export search action screen.

Importing or updating existing search action is done by a cache clear. Go to *Administer/System Settings/Cleanup Caches and update Paths* press *Cleanup Caches*.
There is also an API for importing search actions from code. The api is called *SearchTask.Import*.

**Remark** changes in code are picked up after a cache clear. 