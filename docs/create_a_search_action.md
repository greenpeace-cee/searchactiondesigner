# Howto create a search action.

In this tutorial we are going to create a search action which will create a PDF
for everyone who has an activity of *Send Renewal Reminder* scheduled,
and set the activity to completed.

This tutorial assumes you have successfully installed the Search Action Designer extension.
See here for [how to install](howto_install.md)

## What does our system look like?

In our system Felisha Müller and others have a scheduled activity of the type *Send Renewal Reminder*

![screenshot 1](images/createsearchaction1.png)

We want to create an action which creates the pdf for us and sets the activity to completed.

## Create the search action

Go to **Administer > Customize data and screens > Search Action Designer > Add search action**

* In the next screen select that this search action is available for an activity search.
* Give your search action a title such as 'Create renewal letter'.
* Optionally give it a description. The description is only shown in the administration screen of the search action designer.
* Then enter a short help text, this help text is shown to the user just before executing the search action.
* You can configure the 'Success Message' which is shown to the user after the action has been executed.
* Leave the records per batch. This setting defines how many records are updated in one batch.  If your action is slow you can lower this number.

![screenshot 2](images/createsearchaction2.png)

Press **Next**

## Add the fields to the search action

We want the user to be able to select which message template they want to send and the new status.

### Add the message template field

In the next screen press **Add Field**.

* Select the type *Message Template*.
* Give your field a title and set *Is required*.
* Optionally you can also set a default value.

![screenshot 3](images/createsearchaction3.png)

Press **Save**

### Add the activity status field

When you are back in the Search Action Designer screen click **Add Field** again.

* Select type *Option Group*.
* Give your field a title such as 'New activity status'.
* Check **Is required** as this is a required field.
* In the option group dropdown select the *Activity Status* option group.

![screenshot 4](images/createsearchaction4.png)

Press **Save**

## Add the actions

In this step we will add the actions. The first action is to retrieve the contacts from the activity, the next action
is to create a PDF for this contact, and the last action is to update the status of the activity.

### Action to retrieve the contacts of an activity

In the Search Action Designer screen press **Add Action**

* Select the action type *Get Contact IDs from Activity*.
* Give the action a title, such as 'Contacts'.
* Set **Record Type** to *Activity Targets*
* In the **Parameter mapping** section set the **Activity ID** field to *Activity ID*

![screenshot 5](images/createsearchaction5.png)

Press **Save**

### Action to create the PDF

In the Search Action Designer screen press **Add Action**

* Select the action type *Create PDF*.
* Give the action the title such as 'Renewal letter'
* Leave the filename.
* In the **Parameter mapping** section
  * Set **Contact ID** to *Action :: Contacts :: First found contact ID*
  * Set **Message** to *User input :: Message Template :: Message*

![screenshot 6](images/createsearchaction6.png)

Press **Save**

### Action to update the activity status

In the Search Action Designer screen press **Add Action**

* Select the action type *Update Activity Status*
* Give the action a title.
* In the **Parameter mapping** section
  * Set **Activity ID** to *Activity ID*
  * Set **Status ID** to *User input :: New activity status :: Value*

![screenshot 7](images/createsearchaction7.png)

Press **Save**

## Test the action

Our search action is now finished and it is time to test it.

Go to **Search > Find Activities**. Search on activities of type *Send Renewal Reminder* and with status *Scheduled*

![screenshot 8](images/createsearchaction8.png)

Select the activities which you want to process to send a reminder. In the **Actions** dropdown, select our created action *Create renewal letter*

![screenshot 9](images/createsearchaction9.png)

In the next screen you see the fields we have defined in our search action. We also see the help text.
Select a message template and the status and press Next.

![screenshot 10](images/createsearchaction10.png)

If your batch is large enough a progress bar is shown in the next screen.

![screenshot 11](images/createsearchaction11.png)

After the action is completed you are redirected to your search screen and two popups are shown: one that the action has been completed
and one with a link to download the generated PDF.

![screenshot 12](images/createsearchaction12.png) ![screenshot 13](images/createsearchaction13.png)

## Parameter mapping explanation

The most important thing to understand is the mapping. Most actions require input data and provide output data.
With the mapping we map data from either the search result screen (activity ID, contact ID, etc..), user input or the output of another action to the input of this action.

That is also why we give each action a title, so we have a title in the drop down for parameter mapping.
The same principle applies to the user input (fields). Each field returns one or more values.

