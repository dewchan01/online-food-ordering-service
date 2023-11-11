# Online Food Ordering Service - Domini's House

This project is cloning the Domino's Pizza Website. Hope you enjoy!

---

This project is using XAMPP stack with the PHP
development on the Windows platform.

XAMPP is an *all-in-one* stack that includes PHP, Apache HTTP server, and MariaDB
database. It stands for Cross-Platform (**X**), **A**pache, **M**ariaDB, **P**HP,
and **P**erl. The main advantage is a simple and fast installation of the popular
software for PHP development.

Usually, you won't see it in the production online environments directly because
its main purpose is the PHP development and using it locally on your workstation.

In case of issues, see the [Troubleshooting](#troubleshooting) section at the
bottom of this guide.

## Install XAMPP

Download and install XAMPP from the official [website](https://www.apachefriends.org/index.html).

### Download XAMPP

Head over to the [download section](https://www.apachefriends.org/download.html)
and choose the Windows installer `.exe` for the **PHP 7.2** version. Currently,
only 32-bit versions are available. This won't be an issue at this step even if
you have a 64-bit operating system.

---

### Run the installer

After the download completes, run the installer:

![XAMPP installer](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/installer.png "XAMPP installer")

---

### Confirm app changes

The installer will ask you to confirm changes on your workstation:

![XAMPP allow changes](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/allow-changes.png "XAMPP allow changes")

Click **Yes**.

---

### UAC warning

The following warning is notifying you about the UAC (User Account Control):

```text
Important! Because an activated User Account Control (UAC) on your system
some functions of XAMPP are possibly restricted. With UAC please avoid to
install XAMPP to C:\Program Files (x86) (missing write permissions). Or
deactivate UAC with msconfig after this setup.
```

![XAMPP UAC](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/uac.png "XAMPP UAC")

This means that in case your current Windows system has UAC enabled, you won't
be able to install XAMPP to the `C:\Program Files (x86)` location. You will be
able to install XAMPP elsewhere. For example, in `C:\xampp` folder, which is what
you want actually and this guide suggests further on.

To learn how to disable UAC, follow the procedure in the
[troubleshooting](#troubleshooting) section at the bottom of this guide.

For security purposes avoid disabling the UAC as suggested in the warning and
click **OK**.

---

### Installation wizard

The *Installer's Welcome wizard* screen appears:

![XAMPP Welcome](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/welcome.png "XAMPP Welcome")

Click **Next**.

---

### XAMPP components

The *Components* screen appears. Here you can choose only particular components
that you might need.

![XAMPP Components](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/components.png "XAMPP Components")

A quick components description:

* **Apache**

  This is the main web server that provides something visible at the URL
  `http://localhost`.

* **MySQL**

  This is the main database that will hold your data. This component is actually
  a MariaDB (a fork project of the MySQL), however for the simplicity of
  understanding things, here is called MySQL. Majority of the functionality and
  how to access it with PHP is the same as the MySQL.

* **Filezilla FTP Server**

  An additional component to help you upload files remotely. It won't be used on
  your local machine,

* **Mercury Mail Server**

  Server for sending emails. It won't be used in the local development
  environment.

* **Tomcat**

  This is Apache Tomcat web server for running Java code.

* **PHP**

  This is the main component that you want. PHP language software itself.
  Prebuilt, compiled, packaged, and ready for usage.

* **Perl**

  An additional programming language you might want to check out.

* **phpMyAdmin**

  Control panel with accessible via `http://localhost/phpmyadmin` for managing
  the database.

* **Webalizer**

  A separate web-based log analyzer for statistics and analysis. This won't be
  used on your local machine.

* **Fake Sendmail**

  This is a mailing simulation component that might be useful for sending emails
  on your development machine but not actually delivering them to the real
  address.

This guide will choose all components since they don't change the installation
size or other things much. Click **Next**.

---

### Installation folder

The installation location screen appears. In this guide, the `C:\xampp`. You can
choose to install it wherever you need, except the `C:\Program Files (x86)` as
warned above because of the UAC:

![XAMPP installation folder](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/folder.png "XAMPP installation folder")

Enter the folder location and click **Next**.

---

### Bitnami for XAMPP

The following screen is an information about additional Bitnami for XAMPP add-ons,
which install additional software such as CMS, eCommerce, CRM and similar software
with few button clicks.

![XAMPP Bitnami](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/bitnami.png "XAMPP Bitnami")

Click **Next**.

---

### Ready to install XAMPP

Now you are ready to install the XAMPP stack and all its components.

![XAMPP Ready to install](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/ready.png "XAMPP Ready to install")

Click **Next**.

---

### Installation in progress

The installation procedure is now in progress.

![XAMPP installation](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/installation.png "XAMPP installation")

---

### Firewall

You will also get a notification to configure the firewall rules how the Apache
web server is allowed to communicate on your network. In this guide, the
*Private networks, such as my home or work network* option is chosen.

![XAMPP firewall](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/firewall.png "XAMPP firewall")

Click **Allow**.

---

### Installation is complete

XAMPP installation is now completed.

![XAMPP installation complete](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/completed.png "XAMPP installation complete")

Select to start the XAMPP control panel and click **Finish**.

---

### XAMPP language

XAMPP can be used in more languages. This guide will choose the *English language*.

![XAMPP language](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/language.png "XAMPP language")

Click **Save**.

---

### Control panel

The XAMPP control panel has been launched:

![XAMPP control panel](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/control-panel.png "XAMPP control panel")

Let's start Apache web server and the database. Click start buttons for Apache
and MySQL. You will get a firewall notification for the database service similar
to the one for Apache web server:

![XAMPP MySQL firewall](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/firewall-db.png "XAMPP MySQL firewall")

Click **Allow** for your private network.

---

### Status

The control panel now indicates that Apache and MySQL services are up and running:

![XAMPP control panel started](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/control-panel-2.png "XAMPP control panel started")

---

### Localhost

By visiting `http://localhost` in your browser, you should see an XAMPP welcome
screen, similar to this one:

![XAMPP localhost](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/localhost.png "XAMPP localhost")

---

### Phpmyadmin

For managing the database, you can use the provided phpMyAdmin control panel,
which is available at `http://localhost/phpmyadmin`:

![XAMPP phpMyAdmin](https://raw.githubusercontent.com/phpearth/assets/master/images/docs/install/win/xampp/phpmyadmin.png "XAMPP phpMyAdmin")

---

### Thunderbird Simple Mail Transfer Protocol(SMTP)
Thunderbird is a powerful and customizable open source Email client with millions of users. 
It is based on the Mozilla platform that Firefox is also built on.

<div class="entry-content">
			<p>If you’re using <strong>Mozilla Thunderbird</strong> as a mail client, it’s essential to configure your <strong>SMTP settings</strong> in order to correctly send emails out.</p>
<p>An SMTP (Simple Mail Transfer Protocol) server is simply the machine that takes care of the email delivery process: every provider has its own, with a specific name and address. So when you use Thunderbird to send messages you need to give these instructions to the software, to make it employ the correct <strong>outgoing server</strong> and mail out every message.</p>
<p>Remember however that only a <strong>professional SMTP service</strong> like <a title="turbo SMTP service" href="/en/tsmtpregistration1.php" target="_blank" rel="noopener noreferrer">turboSMTP</a> can ensure you the highest delivery rate (as not any sent emails is automatically delivered, because of antispam filters or bad IP reputation).</p>
<p>Here’s the process to <strong>set up an SMTP for Thunderbird</strong>: open the client, select “Account Settings” from the “Tools Menu” and click on “Outgoing Server (SMTP)”. Then click “Add”: the software will display this popup window:</p>
<p><img decoding="async" fetchpriority="high" class="aligncenter wp-image-791 size-full" src="https://serversmtp.com/wp-content/uploads/2018/03/smtp-for-thunderbird.png" alt="" width="338" height="314" srcset="https://serversmtp.com/wp-content/uploads/2018/03/smtp-for-thunderbird.png 338w, https://serversmtp.com/wp-content/uploads/2018/03/smtp-for-thunderbird-300x279.png 300w, https://serversmtp.com/wp-content/uploads/2018/03/smtp-for-thunderbird-320x297.png 320w, https://serversmtp.com/wp-content/uploads/2018/03/smtp-for-thunderbird-239x222.png 239w" sizes="(max-width: 338px) 100vw, 338px"></p>
<p>Now fill the field with these information:</p>
<ul>
<li>“Description”: enter a general name for your SMTP server.</li>
<li>“Server Name”: enter its address. You can check our list of <a title="What is my SMTP" href="/en/what-is-my-smtp" target="_blank" rel="noopener noreferrer">SMTP settings </a>to find yours.</li>
<li>“Default port”: type 25 (or <a title="Port for SMTP" href="/en/port-for-smtp" target="_blank" rel="noopener noreferrer">another available port</a>:).</li>
<li>“Connection security”: either none or SSL.</li>
<li>“Authentication Method”: you can choose among different options. Read our general article about <a title="How to configure an STMP server" href="/en/smtp-configuration" target="_blank" rel="noopener noreferrer">how to configure an SMTP server</a> to know more.</li>
<li>“User Name”: your email address.</li>
</ul>
<p>Now, after having clicked OK, Mozilla Thundebird is ready to go.</p>
<p>That said, however, remember that in general <strong>Thunderbird is not the right tool for email marketing</strong> (having been created mostly for one-to-one messages and not for mass email sending).</p>
<p>Beyond the problems with SMTP servers, Thunderbird can therefore <strong>generate other difficulties</strong> when it comes to send a newsletter. That’s why we recommend that you rely on a dedicated sending software like <a href="http://www.sendblaster.com">SendBlaster</a>: it’s the best way to manage your email campaigns &amp; lists!</p>
<p>&nbsp;</p>
		</div>

## What's Next?

Congrats! You've successfully installed XAMPP on Windows. The following steps
are recommended to install additional *must-have* tools for the best possible
development experience with PHP. Let's do that also.
