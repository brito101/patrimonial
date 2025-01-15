import pandas as pd

# Carregar a planilha
file_path = "materiais.xlsx"  # Substitua pelo caminho do arquivo
sheet_name = "Sheet1"  # Substitua pelo nome da aba se necessário
df = pd.read_excel(file_path, sheet_name=sheet_name)

# Verificar se existem pelo menos duas colunas
if df.shape[1] < 2:
    print("A planilha deve conter pelo menos duas colunas.")
    exit()

# Selecionar a segunda coluna
col_name = df.columns[1]  # Nome da segunda coluna
second_column = df[col_name]

# Contar as ocorrências de cada valor na segunda coluna
value_counts = second_column.value_counts()

# Filtrar apenas os valores que aparecem mais de uma vez
repeated_values = value_counts[value_counts > 1].index

# Selecionar as linhas com valores repetidos na segunda coluna
duplicated_rows = df[df[col_name].isin(repeated_values)]

duplicated_rows.to_excel("linhas_duplicadas.xlsx", index=False)
print("Linhas duplicadas salvas em 'linhas_duplicadas.xlsx'.")

# Exibir as linhas duplicadas
if not duplicated_rows.empty:
    print("Linhas com valores repetidos na segunda coluna:")
    print(duplicated_rows)
else:
    print("Nenhum valor repetido encontrado na segunda coluna.")
