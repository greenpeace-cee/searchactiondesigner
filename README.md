# Search Action Designer

With this extension you can build your own search actions. 

![Screenshot1](/images/screenshot1.png)
![Screenshot2](/images/screenshot2.png)
![Screenshot3](/images/screenshot3.png)

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v5.6+
* CiviCRM (5.0 or newer)
* [Form Field Library](http://lab.civicrm.org/extensions/formfieldlibrary)
* [Action Provider](http://lab.civicrm.org/extensions/action-provider) (version 1.3 or newer)

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl searchactiondesigner@https://lab.civicrm.org/extensions/searchactiondesigner/-/archive/master/searchactiondesigner-master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://lab.civicrm.org/extensions/searchactiondesigner.git
cv en searchactiondesigner
```

## Usage

After you have installed this extension you can design your search action under Administer --> Customize Data and Screens --> Search Action Designer.

## Documenation

* Creating a search action (to be written)
* Exporting and importing a search action (to be written)

## Developer documentation

* [Hook documentation](docs/hooks.md)
* Storing search action in code your extension (to be written)
