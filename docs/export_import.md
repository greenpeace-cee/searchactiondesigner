# Exporting and importing of search actions

You can also export a search action and import the search action in another CiviCRM installation or environment.
This could be useful if you have defined your search action in your test environment and you want to put it into production.

## Export

Go to *Administer* then *Customize data and screens* and then *Search Action Designer* and behind a search atcion press **Export**
You will then see a screen like below. You can then select all the text in the big text area and copy the text. 

![Export screenshot 1](images/export1.png)

## Import

Go to *Administer* then *Customize data and screens* and then *Search Action Designer* and press **Import Search Action**
In the next screen *paste* the copied text from the export and press **Import**.

![Import screenshot 1](images/import1.png)

**Be careful:** if another search action with the same name already exists in your system this action will be overwritten. 