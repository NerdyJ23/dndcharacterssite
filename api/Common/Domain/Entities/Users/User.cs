using System;

namespace DndCharacterSheets.Common.Domain.Entities.Users;

public sealed class User
{
	public UserId Id { get; set; }
	public required string Username { get; set; }
	public required string Password { get; set; }
	public string? FirstName { get; set; }
	public string? LastName { get; set; }
	public DateTimeOffset CreatedAt { get; set; }
	public DateTimeOffset LastLoggedIn { get; set; }
	public required string Token { get; set; }

}

public readonly record struct UserId
{
	public int Value { get; init; }
	public UserId(int value) { Value = value; }
	public UserId() { }
}