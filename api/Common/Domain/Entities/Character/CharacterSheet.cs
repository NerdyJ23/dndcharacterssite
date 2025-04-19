using System.Collections;
using System.Data;
using DndCharacterSheets.Common.Domain.Entities.Users;

namespace DndCharacterSheets.Common.Domain.Entities.Character;

public sealed class CharacterSheet
{
	public CharacterSheetId Id { get; set; }

	public UserId UserId { get; set; }
	public User? User { get; set; }

	public required string FirstName { get; set; }
	public string? NickName { get; set; }
	public string? LastName { get; set; }
	public required string Race { get; set; }
	public int Experience { get; set; }
	public string? Alignment { get; set; }
	public bool Public { get; set; }

	public CharacterBackgroundId BackgroundId { get; set; }
	public CharacterBackground? Background { get; set; }

	public CharacterHealthId HealthId { get; set; }
	public CharacterHealth? Health { get; set; }

	public int GameId { get; set; }

	public ICollection<CharacterClass> Classes { get; set; } = [];
	public ICollection<CharacterStat> Stats { get; set; } = [];
}

public readonly record struct CharacterSheetId
{
	public int Value { get; init; }
	public CharacterSheetId(int value) { Value = value; }
	public CharacterSheetId() { }
}