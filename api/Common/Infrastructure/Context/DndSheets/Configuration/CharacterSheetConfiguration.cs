using DndCharacterSheets.Common.Domain.Entities.Character;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using MySql.EntityFrameworkCore.Extensions;

namespace DndCharacterSheets.Common.Infrastructure.Context.DndSheets.Configuration;

internal sealed class CharacterSheetConfiguration : IEntityTypeConfiguration<CharacterSheet>
{
	public void Configure(EntityTypeBuilder<CharacterSheet> builder)
	{
		builder.ToTable("Characters");
		builder.HasKey(x => x.Id);

		builder.Property(x => x.Id).HasConversion(IdConversions.CharacterSheetIdConverter()).ValueGeneratedOnAdd().UseMySQLAutoIncrementColumn("int").HasColumnName("ID");

		builder.Property(x => x.FirstName).HasColumnName("First_Name").HasMaxLength(80).ForMySQLHasCollation("latin1_swedish_ci");
		builder.Property(x => x.LastName).HasColumnName("Last_Name").HasMaxLength(100).ForMySQLHasCollation("latin1_swedish_ci");
		builder.Property(x => x.Race).HasMaxLength(255).ForMySQLHasCollation("latin1_swedish_ci");
		builder.Property(x => x.Experience).HasColumnName("Exp").HasDefaultValue(0);
		builder.Property(x => x.Alignment).HasMaxLength(255).ForMySQLHasCollation("latin1_swedish_ci");

		builder.Property(x => x.UserId).HasColumnName("User_Access").HasConversion(IdConversions.UserIdConverter());
		builder.Property(x => x.BackgroundId).HasColumnName("Background_ID").HasConversion(IdConversions.CharacterBackgroundIdConverter());
		builder.Property(x => x.HealthId).HasColumnName("Health_ID").HasConversion(IdConversions.CharacterHealthIdConverter());

		builder.HasOne(x => x.User).WithMany().HasPrincipalKey(x => x.Id).HasForeignKey(x => x.UserId);
		builder.HasOne(x => x.Health).WithOne(x => x.Character).HasForeignKey<CharacterHealth>(x => x.Id).HasPrincipalKey<CharacterSheet>(x => x.HealthId);
		builder.HasOne(x => x.Background).WithOne(x => x.Character).HasForeignKey<CharacterBackground>(x => x.Id).HasPrincipalKey<CharacterSheet>(x => x.BackgroundId);
		builder.HasMany(x => x.Classes).WithOne(x => x.Character).HasForeignKey(x => x.CharacterId).HasPrincipalKey(x => x.Id);
		builder.HasMany(x => x.Stats).WithOne(x => x.Character).HasForeignKey(x => x.CharacterId).HasPrincipalKey(x => x.Id);
	}
}