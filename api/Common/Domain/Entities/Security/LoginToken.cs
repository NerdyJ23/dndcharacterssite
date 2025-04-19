using System;
using DndCharacterSheets.Common.Domain.Entities.Users;

namespace DndCharacterSheets.Common.Domain.Entities.Security;

public sealed class LoginToken
{
	public UserId UserId { get; set; }
	public required string Token { get; set; }
	public DateTimeOffset CreatedAt { get; set; }
	public DateTimeOffset ValidUntil { get; set; }

	public bool IsValid => ValidUntil > DateTimeOffset.Now;
}