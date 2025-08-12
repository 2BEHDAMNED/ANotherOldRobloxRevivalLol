import discord
from discord.ext import commands

from mysqlshit import MySQLShit

TOKEN = 'MTM5MzI3ODE4NjE5OTk3ODA3NQ.Gr0cbX.E1hi3e9TXja_bMTA5zs7hkxRSW9Zkd5pxcQ0Dg'

intents = discord.Intents.default()
intents.members = True
intents.message_content = True

bot = commands.Bot(command_prefix='anorrl/', description="Hiya chat", intents=intents)

@bot.event
async def on_ready():
	print("AnorrlBot has loaded :)")

async def can_dm_user(user: discord.User) -> bool:
	try:
		await user.send()
	except discord.Forbidden:
		return False
	except discord.HTTPException:
		return True
	
	return True

@bot.command()
@commands.has_any_role(1391545857114898513)
async def invite(ctx, member: discord.User):
	"""Invite people via [invite @userhere]"""
	if member.id == bot.user.id:
		return

	if await can_dm_user(member) == False:
		embed = discord.Embed(title="Error")
		embed.description = "<@"+str(member.id)+"> do not have their dms open!-"
		await ctx.send(embed=embed)
	else:
		embed_admin = discord.Embed(title="Success")
		embed_admin.description = "Sent <@"+str(member.id)+"> an access key!"

		access = MySQLShit.generate_access(member)
		if access.startswith("Error: "):
			embed = discord.Embed(title="Error")
			embed.description = access.replace("Error: ", "")
			await ctx.send(embed=embed)
			return

		embed_user = discord.Embed(title="Hello")
		embed_user.description = "Here's your key lalalala\n`"+access+"`"

		await member.send(embed=embed_user)	
		await ctx.send(embed=embed_admin)

@bot.command()
@commands.has_any_role(1391545857114898513)
async def check(ctx, member: discord.User):
	"""Invite people via [invite @userhere] (STAFF ONLY LOL)"""
	embed = discord.Embed(title="User info on ANORRL")
	embed.description = "Placeholder"
	await ctx.send(embed=embed)

@bot.command()
async def twinfantasy(ctx):
	"""My favourite homosexuals"""
	await ctx.send(file=discord.File('twinfantasy.jpg'), content="Car Seat Headrest my beloved <:this:1393321573162684546>")

@bot.event
async def on_command_error(ctx: commands.Context, error: commands.CommandError):
	embed = discord.Embed(title="Error")
	if isinstance(error, commands.errors.MissingAnyRole):
		errormsg = "You do not have the correct permissions to run this command!"
	elif isinstance(error, commands.errors.CommandInvokeError):
		errormsg = "Something went entirely wrong!"
		print(error.__cause__)

	errormsg = errormsg.replace('"', '\\"')
	errormsg = errormsg.replace("'", "\\'")
	embed.description = error

	await ctx.send(embed=embed)

bot.run(TOKEN)