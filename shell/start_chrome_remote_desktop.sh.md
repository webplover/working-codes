### This script lists all manually created users (UID >= 1000 and UID < 65534)  and starts the chrome-remote-desktop service for each user.

1. Create a script file, e.g., `start_chrome_remote_desktop.sh`.

2. Add the following content to the script file:

```bash
#!/bin/bash

# List all manually created users
users=$(awk -F: '$3 >= 1000 && $3 < 65534 { print $1 }' /etc/passwd)

# Iterate over each user and start chrome-remote-desktop service
for user in $users; do
    echo "Starting chrome-remote-desktop for user: $user"
    sudo -u $user systemctl start chrome-remote-desktop@$user
done
```

3. Make the script executable:

```bash
chmod +x start_chrome_remote_desktop.sh
```

4. Run the script:

```bash
./start_chrome_remote_desktop.sh
```

This script will iterate over each manually created user and run the `systemctl start chrome-remote-desktop@target_username` command for them, substituting `target_username` with the actual username.
