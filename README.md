# Introduciton | `moodle-quizaccess_proctor` - 

A Quiz Access Moodle Module adding configurations for Local Proview Plugin integration to Moodle.

This plugin is developed by the team at Talview Inc and implements quiz level configurations for launching “Proview” (which is a proctoring solution developed in Talview) in Moodle LMS. It is an ever growing solution with regular new feature enhancements. The plugin will capture and store the candidate's video while the candidate attempts an exam.

***Note:** This plugin is free to download but the moodle-local_proview plugin needs to be installed to make full use of it.”.*

## Installation

---

-   In the admin view go to Site Administration -> Plugins -> Install Plugin.

-   Download the plugin from Moodle Plugin Directory.

-   Click on “Install the plugin”. You will be directed through some pages, follow the steps.

-   “On the plugin settings page”
    -   Click on Checkbox to enable configurations for the plugin (Default Enabled).

    -   Add The callback URL provided by Talview

    -   Add the username provided by Talview to authenticate the callbacks

    -   Add the password provided by Talview to authenticate the callbacks

-   Installation Completed.

## Post Installation Steps

---

Once the plugin is installed, plugin will initate all the existing quizes with Proctoring and Talview Secure Browser disabled by default. In order to enable Proctoring for a specific quiz please follow the following steps:
-   Go to the quiz where Proctoring needs to be enabled.
-   On the Right Hand Side a settings icon will be available, click on this icon.
-   Click on "Edit Settings"
-   Scroll down till "Proview Proctoring Settings"
-   Here you can select the type of proctoring from dropdown, Talview supports 3 types of proctorin types:
    -   **AI Proctoring:** The session is evaluated by an AI engine, which generates an automated Proview Score.
    -   **Record and Review Proctoring:** The session is evaluated by a proctor after it is complete, and the proctor assigns a Proview Rating.
    -   **Live Proctoring:** The session is evaluated by a proctor while it is ongoing, and the proctor can communicate with the candidate if necessary. The proctor provides the Proview rating.

-   Select the Enable Talview Secure Browser checkbox of TSB has to be enabled

**Note:** Talview Secure Browser will only be launched if the quiz is proview enabled.

## Terms and Conditions

---

Talview Inc and all of its subsidiaries (“Talview”) provides Proview and its related services (“Service”) subject to your compliance with the terms and conditions (“Terms of Service”) set forth.

Talview reserves the right to update and modify the Terms of Service at any time without notice. New features that may be added to the Service shall be subject to the Terms of Service. Should you continue to use the Service after any such modifications have been made, this shall constitute your agreement to such modifications. You may always view the most recent copy of the Terms of Service at "<https://www.talview.com/proview/terms-conditions>"

Violation of any part of the Terms of Service will result in termination of your account.

## Roadmap

---

-   [x] Adding quiz level configuration for proctoring type
-   [x] Adding quiz level configuration for Talview Secure Browser
-   [x] Adding functionality to trigger callbacks to Talview when a quiz is created/updated/deleted
-   [ ] Adding functionality to trigger callbacks to Talview when a participant is added/removed to course
-   [ ] Additional Configuration for showing candidate custom instructions while loading proview
-   [ ] Additional Configuration for adding reference links
-   [ ] Moodle 4.x support
-   [ ] Merging moodle-local_proview and moodle-quizaccess_proctor plugins
