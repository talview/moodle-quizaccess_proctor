# Introduciton | `moodle-quizaccess_proctor`

---

This plugin is developed by the team at Talview Inc and implements quiz-level configurations for launching **Proview** (
a proctoring solution developed in Talview) in Moodle LMS.  
It is an ever-growing solution with regular new feature enhancements. The plugin will capture and store the candidate's
video while the candidate attempts an exam.

**Notes:**

* This plugin is free to download, but the `moodle-local_proview` plugin needs to be installed to make full use of it.
* This plugin is a requirement for installing the `moodle-local_proview` plugin, the installation guide for this plugin
  can be found [here](https://proviewsupport.freshdesk.com/support/solutions/articles/81000384579).

## Installation

---

1. Navigate to Site Administration in the admin view.
2. Proceed to Plugins, and then select Install Plugin.
3. Download the plugin
   from [https://assets.talview.com/moodle-quiz-plugin/v1.0.0/proctor.zip](https://assets.talview.com/moodle-quiz-plugin/v1.0.0/proctor.zip) (
   latest version V1.0.0).
4. Click on "Install the plugin" and follow the on-screen instructions through the subsequent pages.
5. On the plugin settings page:
    * Enable configurations for the plugin by selecting the Checkbox (Default Enabled).
    * Enter the callback URL provided by Talview.
    * Input the username provided by Talview for authenticating the callbacks.
    * Provide the password provided by Talview for authenticating the callbacks.
6. The installation process is now completed.

## Post Installation Steps

---

After installing the plugin, it will set all existing quizzes to have Proctoring and Talview Secure Browser disabled by
default. To enable Proctoring for a specific quiz, follow these steps:

1. Go to the quiz for which Proctoring needs to be enabled.
2. On the Right Hand Side, you will find a settings icon; click on this icon.
3. Select "Edit Settings."
4. Scroll down to find "Proview Proctoring Settings."
5. In this section, choose the type of proctoring from the dropdown. Talview supports three types of proctoring:
    * **AI Proctoring**: The session is evaluated by an AI engine, which generates an automated Proview Score.
    * **Record and Review**: Proctoring: The session is evaluated by a proctor after it is complete, and the proctor
      assigns a Proview Rating.
    * **Live Proctoring**: The session is evaluated by a proctor while it is ongoing, and the proctor can communicate
      with the candidate if necessary. The proctor provides the Proview rating.
6. To enable Talview Secure Browser, select the "Enable Talview Secure Browser" checkbox, but please note that TSB will
   only be launched if the quiz is Proview Proctoring enabled.

## Terms and Conditions

---

Talview Inc and all of its subsidiaries (“Talview”) provides Proview and its related services (“Service”) subject to
your compliance with the terms and conditions (“Terms of Service”) set forth.
Talview reserves the right to update and modify the Terms of Service at any time without notice. New features that may
be added to the Service shall be subject to the Terms of Service. Should you continue to use the Service after any such
modifications have been made, this shall constitute your agreement to such modifications. You may always view the most
recent copy of the Terms of Service at "<https://www.talview.com/proview/terms-conditions>"
Violation of any part of the Terms of Service will result in termination of your account.

## Roadmap

---

-   [x] Adding quiz level configuration for proctoring type
-   [x] Adding quiz level configuration for Talview Secure Browser
-   [x] Adding functionality to trigger callbacks to Talview when a quiz is created/updated/deleted
-   [x] Adding functionality to trigger callbacks to Talview when a participant is added/removed to course
-   [x] Additional Configuration for showing candidate custom instructions while loading proview
-   [x] Additional Configuration for adding reference links
-   [ ] Moodle 4.x support
-   [ ] Merging moodle-local_proview and moodle-quizaccess_proctor plugins
