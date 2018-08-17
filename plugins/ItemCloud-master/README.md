Hi, I will introduce my new plugin : ItemCloud

#### What is ItemCloud?

ItemCloud is a separated plugin from EconomyPShop, and will be used as library for EconomyPShop in future.
This plugin provides similar functionality to DropBox or Box and etc. It is just for items. You can upload and download unlimited items. It is useful when your inventory is lacking slots, or storing important items.

First you must register: `/itemcloud register`

Then upload items: `/itemcloud upload <item ID[:item damage]>`


#### Aliases

`/itmc`

`/itmc up 276 5`

`/itmc down 276 5`


#### Features

- Upload and download your items infinitely
- API system
- Data auto save


#### Commands

`/itemcloud register or reg`: You MUST use this command before using the service.

`/itemcloud upload or up <item ID[:item damage]> <count>` : Upload items to your account.

`/itemcloud download or down <item ID[:item damage]> <count>` : Download items from your account.

`/itemcloud list` : List all the items in your account.

`/itemcloud count <item id>` : Display the item count of a specific item.


#### Permissions

`itemcloud.*`

`itemcloud.command.*`

`itemcloud.command.register`

`itemcloud.command.upload`

`itemcloud.command.download`

`itemcloud.command.list`

`itemcloud.command.count`


#### By Onebone