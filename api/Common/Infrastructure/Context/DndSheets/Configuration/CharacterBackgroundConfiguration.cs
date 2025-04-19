using DndCharacterSheets.Common.Domain.Entities.Character;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using MySql.EntityFrameworkCore.Extensions;

namespace DndCharacterSheets.Common.Infrastructure.Context.DndSheets.Configuration;

internal sealed class CharacterBackgroundConfiguration : IEntityTypeConfiguration<CharacterBackground>
{
	public void Configure(EntityTypeBuilder<CharacterBackground> builder)
	{
		builder.ToTable("Characters_Background");
		builder.HasKey(x => x.Id);

		builder.Property(x => x.Id).HasConversion(IdConversions.CharacterBackgroundIdConverter()).ValueGeneratedOnAdd().UseMySQLAutoIncrementColumn("int").HasColumnName("ID");
		builder.Property(x => x.CharacterId).HasConversion(IdConversions.CharacterSheetIdConverter()).HasColumnName("Char_ID");
		builder.Property(x => x.Name).HasMaxLength(50).ForMySQLHasCollation("utf8mb4_0900_ai_ci");
		builder.Property(x => x.Description).HasMaxLength(200).ForMySQLHasCollation("utf8mb4_0900_ai_ci");
	}
}