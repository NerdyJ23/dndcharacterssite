using System.Collections;

namespace DndCharacterSheets.Common.Domain.Entities.Character;

public sealed class CharacterSheet
{
	public CharacterSheetId Id { get; set; }
	public int UserAccess { get; set; }
	public required string FirstName { get; set; }
	public string? NickName { get; set; }
	public string? LastName { get; set; }
	public required string Race { get; set; }
	public int Experience { get; set; }
	public string? Alignment { get; set; }
	public bool Public { get; set; }

	public int BackgroundId { get; set; }
	public int GameId { get; set; }
	public ICollection<ClassList> Classes { get; set; }
}

public sealed record struct CharacterSheetId
{
	public int Value { get; init; }
	public CharacterSheetId(int value) { Value = value; }
	public CharacterSheetId() { }
}