namespace DndCharacterSheets.Common.Domain.Entities.Character;

public sealed record CharacterBackground
{
	public CharacterBackgroundId Id { get; set; }
	public CharacterSheetId CharacterId { get; set; }
	public CharacterSheet? Character { get; set; }

	public required string Name { get; set; }
	public string? Description { get; set; }
}

public readonly record struct CharacterBackgroundId
{
	public int Value { get; init; }
	public CharacterBackgroundId(int value) { Value = value; }
	public CharacterBackgroundId() { }
}