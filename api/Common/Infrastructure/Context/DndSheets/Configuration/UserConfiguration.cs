using DndCharacterSheets.Common.Domain.Entities.Users;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using MySql.EntityFrameworkCore.Extensions;

namespace DndCharacterSheets.Common.Infrastructure.Context.DndSheets.Configuration;

internal sealed class UserConfiguration : IEntityTypeConfiguration<User>
{
	public void Configure(EntityTypeBuilder<User> builder)
	{
		builder.ToTable("Users");
		builder.HasKey(x => x.Id);
		builder.Property(x => x.Id).HasConversion(IdConversions.UserIdConverter()).HasColumnName("ID").ValueGeneratedOnAdd().UseMySQLAutoIncrementColumn("int");
		builder.Property(x => x.Username).HasMaxLength(255).ForMySQLHasCollation("latin1_swedish_ci");
		builder.Property(x => x.Password).HasMaxLength(255).ForMySQLHasCollation("latin1_swedish_ci");
		builder.Property(x => x.FirstName).HasMaxLength(80).HasColumnName("First_Name").ForMySQLHasCollation("latin1_swedish_ci");
		builder.Property(x => x.LastName).HasMaxLength(80).HasColumnName("Last_Name").ForMySQLHasCollation("latin1_swedish_ci");
		builder.Property(x => x.CreatedAt).HasColumnName("Created_At").HasConversion<long>();
		builder.Property(x => x.LastLoggedIn).HasColumnName("Last_Logged_In").HasConversion<long>();
		builder.Property(x => x.Token).HasMaxLength(500).ForMySQLHasCollation("latin1_swedish_ci");
	}
}