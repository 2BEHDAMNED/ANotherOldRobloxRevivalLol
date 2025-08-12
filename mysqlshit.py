import mysql.connector
import discord
import uuid

class MySQLShit:
	@staticmethod
	def create_connection():
		return mysql.connector.connect(host="localhost", user="anorrl", password="jvU-N@QG[NmVMEJK", database="anorrldb")

	@staticmethod
	def generate_access(user: discord.User):
		mydb = MySQLShit.create_connection()

		cursor = mydb.cursor()
		cursor.execute("SELECT `access_discorduid` FROM `accesskeys` WHERE `access_discorduid` = "+ str(user.id))
		result_access = cursor.fetchall()

		cursor.execute("SELECT `user_discord` FROM `users` WHERE `user_discord` = "+str(user.id))
		result_users = cursor.fetchall()

		if len(result_access) == 0 and len(result_users) == 0:
			key = uuid.uuid4().__str__()

			cursor = mydb.cursor()

			sql = "INSERT INTO `accesskeys` (`access_key`, `access_discorduid`) VALUES (%s, %s)"
			val = (key, str(user.id))
			cursor.execute(sql, val)

			mydb.commit()

			return key
		
		if len(result_access) != 0 and len(result_users) != 0:
			return "Error: <@"+str(user.id)+"> already has a key pending AND has an account already...?????"
		elif len(result_access) != 0:
			return "Error: <@"+str(user.id)+"> already has a key pending..."
		elif len(result_users) != 0:
			return "Error: <@"+str(user.id)+"> already has an account!"

		return None