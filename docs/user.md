# User management

## user:invite

Used to invite users to existing projects. If a user with the given email already exists he or she will be added to the project immediately otherwise an invitation email will be send.  

| Argument   | Description | Example | 
| ----------- | ----------- | --------|
| Project Identifier      | The project identifier. Can be seen as first numeric parameter in the URL in koality.io       | 969
| Invitee email address   | The email address of the person that should be invited.     | user@example.com

### Example

```shell
 php bin/engine.php user:invite 969 user@example.com
```
