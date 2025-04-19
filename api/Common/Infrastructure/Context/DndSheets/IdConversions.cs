using DndCharacterSheets.Common.Domain.Entities.Character;
using DndCharacterSheets.Common.Domain.Entities.Users;
using Microsoft.EntityFrameworkCore.Storage.ValueConversion;

namespace DndCharacterSheets.Common.Infrastructure.Context.DndSheets;

internal static class IdConversions
{
	public static ValueConverter<CharacterSheetId, int> CharacterSheetIdConverter() => new(to => to.Value, from => new(from));
	public static ValueConverter<CharacterBackgroundId, int> CharacterBackgroundIdConverter() => new(to => to.Value, from => new(from));
	public static ValueConverter<CharacterClassId, int> CharacterClassIdConverter() => new(to => to.Value, from => new(from));
	public static ValueConverter<CharacterHealthId, int> CharacterHealthIdConverter() => new(to => to.Value, from => new(from));
	public static ValueConverter<CharacterStatId, int> CharacterStatIdConverter() => new(to => to.Value, from => new(from));

	public static ValueConverter<UserId, int> UserIdConverter() => new(to => to.Value, from => new(from));
}